<div class="search-container">
<div class="searchbox">
    <form id="searchForm">
    <i class="bx bx-search"></i>
        <input type="text"class="searchinput" name="txt" onmouseout="this.value = ''; this.blur();">
    </form>
</div>
</div>
<div class="text">User not found.</div>

<script>
    // Get a reference to the search form
    const searchForm = document.getElementById("searchForm");

    // Add an event listener to the form submission
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the user input from the search input field
        const userInput = searchForm.elements["txt"].value;

        // Construct the desired URL with the user's input
        const desiredURL = `${userInput}`;

        // Redirect the user to the desired URL
        window.location.href = desiredURL;
    });
</script>