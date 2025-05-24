<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'is_active'];
    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'coupon_order', 'coupon_id', 'order_id');
    }

    public static function isActive(string $code): ?Coupon
    {
        return self::where('code', $code)->where('is_active', true)->first();
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where('code', 'like', '%' . $filters['search'] . '%');
        }
    }

    public static function findForShow($id)
    {
        try {
            $coupon = self::findOrFail($id);
            return [
                'success' => true,
                'coupon' => $coupon,
                'updated_at' => $coupon->updated_at->toIso8601String(),
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching coupon: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Không thể tải thông tin mã giảm giá.',
            ];
        }
    }

    public static function createWithValidation(array $data)
    {
        try {
            // Define validation rules
            $validator = Validator::make($data, [
                'code' => 'required|string|unique:coupons,code|max:50',
                'type' => 'required|in:percent,fixed',
                'value' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($data) {
                        self::validateValue($value, $data['type'] ?? '', $fail);
                    },
                ],
                'is_active' => 'boolean',
            ], [
                'code.required' => 'Mã giảm giá là bắt buộc.',
                'code.unique' => 'Mã giảm giá đã tồn tại.',
                'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
                'type.required' => 'Loại mã giảm giá là bắt buộc.',
                'type.in' => 'Loại mã giảm giá không hợp lệ.',
                'value.required' => 'Giá trị mã giảm giá là bắt buộc.',
                'value.numeric' => 'Giá trị phải là số.',
                'value.min' => 'Giá trị không được nhỏ hơn 0.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation error creating coupon: ' . json_encode($validator->errors()));
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $validator->errors()->toArray(),
                ];
            }

            $coupon = self::create([
                'code' => $data['code'],
                'type' => $data['type'],
                'value' => $data['value'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            return [
                'success' => true,
                'message' => 'Mã giảm giá được tạo thành công.',
                'coupon' => $coupon,
                'updated_at' => $coupon->updated_at->toIso8601String(),
            ];
        } catch (\Exception $e) {
            Log::error('Error creating coupon: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo mã giảm giá.',
            ];
        }
    }
    public static function findForUpdate($id)
    {
        try {
            // Retrieve the coupon, including soft-deleted ones
            $coupon = static::findOrFail($id);

            // Check if the coupon is soft-deleted
            if (!$coupon) {
                return [
                    'success' => false,
                    'message' => 'Không thể cập nhật mã giảm giá vì mã này đã bị xóa.',
                ];
            }

            return $coupon;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Coupon not found: ' . $e->getMessage(), ['coupon_id' => $id]);
            return [
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại.',
            ];
        }
    }

    public function updateWithValidation(array $data)
    {
        try {
            // Ensure the model is loaded
            if (!$this->exists) {
                Log::error('Coupon model not loaded or does not exist.', ['id' => $this->id ?? 'unknown']);
                return [
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại.',
                ];
            }

            // Define validation rules
            $validator = Validator::make($data, [
                'code' => 'required|string|max:50' . $this->coupon_id . ',id',
                'type' => 'required|in:percent,fixed',
                'value' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($data) {
                        self::validateValue($value, $data['type'] ?? '', $fail);
                    },
                ],
                'is_active' => 'boolean',
                'updated_at' => 'required|date',
            ], [
                'code.required' => 'Mã giảm giá là bắt buộc.',
                'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
                'type.required' => 'Loại mã giảm giá là bắt buộc.',
                'type.in' => 'Loại mã giảm giá không hợp lệ.',
                'value.required' => 'Giá trị mã giảm giá là bắt buộc.',
                'value.numeric' => 'Giá trị phải là số.',
                'value.min' => 'Giá trị không được nhỏ hơn 0.',
                'updated_at.required' => 'Timestamp cập nhật là bắt buộc.',
                'updated_at.date' => 'Timestamp cập nhật không hợp lệ.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation error updating coupon: ' . json_encode($validator->errors()), ['coupon_id' => $this->coupon_id]);
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $validator->errors()->toArray(),
                ];
            }

            // Check if updated_at is a valid Carbon instance
            if (!$this->updated_at instanceof \Carbon\Carbon) {
                try {
                    $this->updated_at = \Carbon\Carbon::parse($this->updated_at);
                } catch (\Exception $e) {
                    Log::error('Cannot parse updated_at timestamp.', [
                        'coupon_id' => $this->coupon_id,
                        'value' => $this->updated_at,
                    ]);
                    return [
                        'success' => false,
                        'message' => 'Timestamp không hợp lệ.',
                    ];
                }
            }


            // Compare timestamps
            if ($this->updated_at->toIso8601String() !== $data['updated_at']) {
                Log::info('Timestamp mismatch for coupon.', [
                    'coupon_id' => $this->coupon_id,
                    'server' => $this->updated_at->toIso8601String(),
                    'request' => $data['updated_at'],
                ]);
                return [
                    'success' => false,
                    'message' => 'Mã giảm giá đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu.',
                    'updated_at_server' => $this->updated_at->toIso8601String(),
                    'updated_at_request' => $data['updated_at'],
                ];
            }

            // Update the coupon
            $this->update([
                'code' => $data['code'],
                'type' => $data['type'],
                'value' => $data['value'],
                'is_active' => $data['is_active'] ?? $this->is_active,
            ]);

            return [
                'success' => true,
                'message' => 'Mã giảm giá được cập nhật thành công.',
                'updated_at' => $this->fresh()->updated_at->toIso8601String(),
            ];
        } catch (\Exception $e) {
            Log::error('Error updating coupon: ' . $e->getMessage(), [
                'coupon_id' => $this->coupon_id ?? 'unknown',
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật mã giảm giá: ' . $e->getMessage(),
            ];
        }
    }

    // Assuming validateValue is defined elsewhere in the model
    public static function validateValue($value, $type, $fail)
    {
        // Your existing validation logic for value based on type
        if ($type === 'percent' && $value > 100) {
            $fail('Giá trị phần trăm không được vượt quá 100.');
        }
    }

    public function deleteWithValidation($updated_at)
    {
        try {
            // Validate updated_at
            $validator = Validator::make(['updated_at' => $updated_at], [
                'updated_at' => 'required|date',
            ], [
                'updated_at.required' => 'Timestamp cập nhật là bắt buộc.',
                'updated_at.date' => 'Timestamp cập nhật không hợp lệ.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation error deleting coupon: ' . json_encode($validator->errors()));
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $validator->errors()->toArray(),
                ];
            }

            if ($this->updated_at->toIso8601String() !== $updated_at) {
                return [
                    'success' => false,
                    'message' => 'Mã giảm giá đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu.',
                    'updated_at_server' => $this->updated_at->toIso8601String(), // Server timestamp
                    'updated_at_request' => $updated_at, // Incoming timestamp from request
                ];
            }


            try {
                if ($this->orders()->exists()) {
                    Log::info('Coupon cannot be deleted due to existing orders.', [
                        'coupon_id' => $this->id,
                        'order_count' => $this->orders()->count(),
                    ]);
                    return [
                        'success' => false,
                        'message' => 'Không thể xóa mã giảm giá vì đã được sử dụng trong đơn hàng.',
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error checking orders relationship for coupon: ' . $e->getMessage(), [
                    'coupon_id' => $this->id,
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString(),
                ]);
                return [
                    'success' => false,
                    'message' => 'Lỗi khi kiểm tra đơn hàng liên quan.',
                ];
            }

            $this->delete();

            return [
                'success' => true,
                'message' => 'Mã giảm giá được xóa thành công.',
            ];
        } catch (\Exception $e) {
            Log::error('Error deleting coupon: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa mã giảm giá.',
            ];
        }
    }
}
