document.querySelectorAll(".sort-toggle").forEach(button => {
    button.addEventListener("click", () => {
        const currentDir = button.dataset.dir;
        const newDir = currentDir === "asc" ? "desc" : "asc";
        const sort = button.dataset.sort;

        const url = new URL(window.location.href);
        url.searchParams.set("sort", sort);
        url.searchParams.set("dir", newDir);
        window.location.href = url.toString();
    });
});

$(document).on("change", ".update-status", function () {
    const orderId = $(this).data("order-id");
    const newStatus = $(this).val();

    $.ajax({
        url: "update_order_status.php",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({ order_id: orderId, order_status: newStatus }),
        success: function (response) {
            if (response.success) {
                alert("Order status updated!");
            } else {
                alert("Update failed: " + (response.error || "Unknown error"));
            }
        },
        error: function () {
            alert("Server error. Try again.");
        }
    });
});

const currencyOptions = { style: "currency", currency: "PHP" };

$(document).on("click", ".view-order-details", function () {
    let orderId = $(this).data("order-id");

    $.ajax({
        url: "functions/get_order_details.php",
        type: "POST",
        data: { order_id: orderId },
        success: function (response) {

            if (response.error) {
                Swal.fire("Oops!", response.message, "error");
                return;
            }

            $("#order_reference_number").text(response.order_details.reference_number);
            $("#order_customer_name").text(response.order_details.customer_name) // Full name
            $("#order_price_total").text(Number(response.order_details.price_total).toLocaleString("en-US", currencyOptions));
            $("#order_courier").text(response.order_details.courier_name);
            $("#order_address").text(response.order_details.address);
            $("#order_date").text(response.order_details.order_date);
            $("#order_required_date").text(response.order_details.required_date);
            $("#order_shipping_date").text(response.order_details.shipping_date || "Not shipped yet");
            $("#order_comments").text(response.order_details.comments || "No comments");

            let productHTML = response.ordered_products.map(product => `
                <tr>
                    <td>${product.name}</td>
                    <td>${Number(product.price_each).toLocaleString("en-US", currencyOptions)}</td>
                    <td>${product.quantity || "0"}</td>
                    <td>${product.avail_engraving ? "Yes" : "No"}</td>
                    <td>${product.avail_giftbox ? "Yes" : "No"}</td>
                </tr>
            `).join("");

            $("#order-products-tbody").html(productHTML);
            $("#orderDetailsModal").modal("show");
        },
        error: function () {
            Swal.fire("Oops!", "Failed to load order details.", "error");
        }
    });
});

$("#closeOrderDetailsModal").on("click", function () {
    $("#orderDetailsModal").modal("hide");
});
