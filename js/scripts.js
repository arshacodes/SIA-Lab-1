$("#search-shop-box").on("keyup", function () {
    let value = $(this).val().toLowerCase();

    $(".product-item").each(function () {
        let productName = $(this).find(".product-name").text().toLowerCase();

        $(this).toggle(productName.includes(value));
    });
});

$("#search-shop-box, #category-filter").on("input change", function () {
    let searchValue = $("#search-shop-box").val().toLowerCase();
    let selectedCategory = $("#category-filter").val();

    $(".product-item").each(function () {
        let productName = $(this).find(".product-name").text().toLowerCase();
        let productCategory = $(this).data("category");

        let matchesSearch = productName.includes(searchValue);
        let matchesCategory = selectedCategory === "" || productCategory == selectedCategory;

        $(this).toggle(matchesSearch && matchesCategory);
    });
});