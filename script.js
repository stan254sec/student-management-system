// Get the search box element
const searchBox = document.getElementById('searchBox');

// Listen for every key press
searchBox.addEventListener('keyup', function () {

    const searchValue = this.value;

    // Send AJAX request
    fetch('search_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'search=' + encodeURIComponent(searchValue)
    })
    .then(function (response) {
        return response.text();
    })
    .then(function (data) {
        // Update results div without reloading page
        document.getElementById('searchResults').innerHTML = data;
    })
    .catch(function (error) {
        console.error('Error:', error);
    });

});