
// next one

$(document).ready(function() {
    $('#loadItems').on('click', function() {
        $.ajax({
            url: 'load_items.php',
            type: 'GET',
            success: function(data) {
                $('#itemsList').html(data);
            },
            error: function() {
                alert('Error loading items.');
            }
        });
    });

    $('#addItemForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_item_process.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#response').html(response);
                $('#addItemForm')[0].reset(); // Reset form
                $('#loadItems').trigger('click'); // Reload items
            },
            error: function() {
                alert('Error adding item.');
            }
        });
    });
});
