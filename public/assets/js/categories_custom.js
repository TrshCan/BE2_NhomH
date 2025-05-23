/* JS Document */

/******************************

[Table of Contents]

1. Vars and Inits
2. Set Header
3. Init Menu
4. Init Favorite
5. Init Fix Product Border
6. Init Isotope Filtering
7. Init Checkboxes

******************************/
console.log('categories_custom.js loaded');

jQuery(document).ready(function ($) {
  "use strict";

  /* 
    1. Vars and Inits
    */
  var header = $(".header");
  var hamburger = $(".hamburger_container");
  var menu = $(".hamburger_menu");
  var menuActive = false;
  var hamburgerClose = $(".hamburger_close");

  setHeader();

  $(window).on("resize", function () {
    initFixProductBorder();
    setHeader();
  });

  $(document).on("scroll", function () {
    setHeader();
  });

  initMenu();
  initFavorite();
  initFixProductBorder();
  initIsotopeFiltering();
  initCheckboxes();

  /* 
    2. Set Header
    */
  function setHeader() {
    if (window.innerWidth < 992) {
      if ($(window).scrollTop() > 100) {
        header.addClass("shadow-sm").css({ top: "0" });
      } else {
        header.removeClass("shadow-sm").css({ top: "0" });
      }
    } else {
      if ($(window).scrollTop() > 100) {
        header.addClass("shadow-sm").css({ top: "0" });
      } else {
        header.removeClass("shadow-sm").css({ top: "0" });
      }
    }
    if (window.innerWidth > 991 && menuActive) {
      closeMenu();
    }
  }

  /* 
    3. Init Menu
    */
  function initMenu() {
    if (hamburger.length) {
      hamburger.on("click", function () {
        if (!menuActive) {
          menu.offcanvas("show");
          menuActive = true;
        }
      });
    }

    if (hamburgerClose.length) {
      hamburgerClose.on("click", function () {
        if (menuActive) {
          menu.offcanvas("hide");
          menuActive = false;
        }
      });
    }

    // Handle dropdown menu items
    if ($(".menu_item.dropdown").length) {
      $(".menu_item.dropdown").on("click", function (e) {
        e.preventDefault();
        var dropdownMenu = $(this).find(".dropdown-menu");
        if (dropdownMenu.is(":visible")) {
          dropdownMenu.slideUp();
        } else {
          dropdownMenu.slideDown();
        }
      });
    }
  }

  function openMenu() {
    menu.offcanvas("show");
    menuActive = true;
  }

  function closeMenu() {
    menu.offcanvas("hide");
    menuActive = false;
  }

  /* 
    4. Init Favorite
    */
  function initFavorite() {
    if ($(".favorite").length) {
      var favs = $(".favorite");

      favs.each(function () {
        var fav = $(this);
        var active = fav.hasClass("active");

        fav.on("click", function () {
          if (active) {
            fav.removeClass("active");
            active = false;
          } else {
            fav.addClass("active");
            active = true;
          }
        });
      });
    }
  }

  /* 
    5. Init Fix Product Border
    */
  function initFixProductBorder() {
    if ($(".product_filter").length) {
      var products = $(".product_filter:visible");
      var wdth = window.innerWidth;

      // Reset border
      products.each(function () {
        $(this).css("border-right", "solid 1px #e9e9e9");
      });

      // Adjust borders based on window width
      if (wdth < 576) {
        products.each(function () {
          $(this).css("border-right", "none");
        });
      } else if (wdth < 768) {
        for (var i = 1; i < products.length; i += 2) {
          var product = $(products[i]);
          product.css("border-right", "none");
        }
      } else if (wdth < 992) {
        for (var i = 2; i < products.length; i += 3) {
          var product = $(products[i]);
          product.css("border-right", "none");
        }
      } else {
        for (var i = 3; i < products.length; i += 4) {
          var product = $(products[i]);
          product.css("border-right", "none");
        }
      }
    }
  }

  /* 
    6. Init Isotope Filtering
    */
  function initIsotopeFiltering() {
    var sortTypes = $(".type_sorting_btn");
    var sortNums = $(".num_sorting_btn");
    var filterButton = $(".filter_button");

    if ($(".product-grid").length) {
      $(".product-grid").isotope({
        itemSelector: ".product-item",
        getSortData: {
          price: function (itemElement) {
            var priceEle = $(itemElement)
              .find(".product_price")
              .text()
              .replace(/[^\d]/g, ""); // Loại bỏ ký tự không phải số (đ, dấu chấm, dấu cách)
            return parseFloat(priceEle);
          },
          name: function (itemElement) {
            return $(itemElement).find(".product_name").text();
          },
        },
        animationOptions: {
          duration: 750,
          easing: "linear",
          queue: false,
        },
      });

      // Sort based on the value from the sorting_type dropdown
      sortTypes.each(function () {
        $(this).on("click", function () {
          $(".type_sorting_text").text($(this).text());
          var option = $(this).attr("data-isotope-option");
          option = JSON.parse(option);
          $(".product-grid").isotope(option);
        });
      });

      // Show only a selected number of items
      sortNums.each(function () {
        $(this).on("click", function () {
          var numSortingText = $(this).text();
          $(".num_sorting_text").text(numSortingText);
          var numFilter = ":nth-child(-n+" + numSortingText + ")";
          $(".product-grid").isotope({ filter: numFilter });
          initFixProductBorder();
        });
      });

      // Filter based on checkboxes (categories, types, brands)
      $(".custom-checkbox").on("change", function () {
        var filters = [];
        $(".custom-checkbox:checked").each(function () {
          var filterValue = $(this).data("filter");
          if (filterValue) {
            filters.push("." + filterValue);
          }
        });
        var filterString = filters.length > 0 ? filters.join(", ") : "*";
        $(".product-grid").isotope({ filter: filterString });
        initFixProductBorder();
      });

      // Filter based on the price range inputs
      filterButton.on("click", function () {
        var priceMin = parseFloat($("#price-min").val()) || 0;
        var priceMax = parseFloat($("#price-max").val()) || 37500000; // Tương đương 1500 USD

        if (priceMin < 0) priceMin = 0;
        if (priceMax < priceMin) priceMax = priceMin;

        $(".product-grid").isotope({
          filter: function () {
            var itemPrice = parseFloat(
              $(this)
                .find(".product_price")
                .text()
                .replace(/[^\d]/g, "") // Loại bỏ ký tự không phải số
            );
            return itemPrice >= priceMin && itemPrice <= priceMax;
          },
          animationOptions: {
            duration: 750,
            easing: "linear",
            queue: false,
          },
        });
        initFixProductBorder();
      });
    }
  }

  /* 
    7. Init Checkboxes
    */
  function initCheckboxes() {
    if ($(".custom-checkbox").length) {
      var checkboxes = $(".custom-checkbox");

      checkboxes.each(function () {
        var checkbox = $(this);
        checkbox.on("change", function () {
          if (checkbox.is(":checked")) {
            checkbox.parent().addClass("active");
          } else {
            checkbox.parent().removeClass("active");
          }
        });
      });

      if ($(".show_more").length) {
        $(".show_more").each(function () {
          var showMoreBtn = $(this);
          var targetClass = showMoreBtn.data("target");
          var checkboxList = showMoreBtn
            .closest(".sidebar_section")
            .find("." + targetClass);

          // Ẩn các mục .hidden-item ban đầu
          checkboxList.find(".hidden-item").hide();
          showMoreBtn.show();

          showMoreBtn.on("click", function (e) {
            e.preventDefault();
            checkboxList.toggleClass("show-all");

            if (checkboxList.hasClass("show-all")) {
              checkboxList.find(".hidden-item").show();
              showMoreBtn.html('<i class="fas fa-minus me-1"></i> Ẩn bớt');
            } else {
              checkboxList.find(".hidden-item").hide();
              showMoreBtn.html('<i class="fas fa-plus me-1"></i> Xem thêm');
            }
          });
        });
      }
    }
  }
});
// Đảm bảo đang dùng jQuery
$(".custom-checkbox").on("change", function () {
    var filters = [];

    $(".custom-checkbox:checked").each(function () {
        var filterValue = $(this).data("filter");
        if (filterValue) {
            filters.push("." + filterValue);
        }
    });

    var filterString = filters.length > 0 ? filters.join(", ") : "*";
    $(".product-grid").isotope({ filter: filterString });
});

// Nút "Xóa bộ lọc"
$("#clear-category-filter").on("click", function () {
    $(".custom-checkbox").prop("checked", false);
    $(".product-grid").isotope({ filter: "*" });
});
