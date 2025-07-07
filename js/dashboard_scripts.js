//SALES=======================================================
// ORDERS TAB
$(document).on("change", ".update-order-status", function () {
  const orderId   = $(this).data("order-id");
  const newStatus = $(this).val();

  const rawStatus = $(this).val();

//   console.log("Sending:", { orderId, rawStatus });

  $.ajax({
    url: "functions/update_order_status.php",
    method: "POST",
    contentType: "application/json",
    data: JSON.stringify({ order_id: orderId, order_status: newStatus }),

    success: function (res) {
        Swal.fire({
          icon: 'success',
          title: 'Done!',
          text: 'Order status updated.',
          timer: 1500,
          showConfirmButton: false
        });
    },

    error: function () {
      Swal.close();
      Swal.fire({
        icon: 'error',
        title: 'Server error',
        text: 'Could not reach the server.'
      });
    }
  });
});

$(document).on("click", ".view-order-details", function () {
  const modal = document.getElementById("orderDetailsModal");
  const modalInstance = new bootstrap.Modal(modal);
  modalInstance.show();

  // Set modal contents from data attributes:
  $("#order_reference_number").text($(this).data("reference-code"));
  $("#order_customer_name").text($(this).data("customer-name"));
  $("#order_price_total").text("₱" + parseFloat($(this).data("total-amount")).toFixed(2));
  $("#order_courier").text($(this).data("courier-service"));
  $("#order_address").text($(this).data("address"));
  $("#order_date").text($(this).data("checkout-date"));
  $("#order_required_date").text($(this).data("required-date"));
  $("#order_shipping_date").text($(this).data("shipping-date") || "Not yet shipped");
  $("#order_comments").text($(this).data("comments") || "—");

  const orderId = $(this).data("order-id");

  // Load order items via AJAX
  $.get("get_order_items.php", { id: orderId }, function (html) {
    $("#order-products-tbody").html(html);
  });
});

$("#closeOrderDetailsModal").on("click", function () {
  $("#orderDetailsModal").modal("hide");
});

$("#search-orders-box").on("keyup", function () {
  const keyword = $(this).val().toLowerCase();

  $("table tbody tr").each(function () {
    const match = $(this).text().toLowerCase().includes(keyword);
    $(this).toggle(match);
  });
});

//PRODUCTS TAB

