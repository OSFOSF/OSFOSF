<?php
/**
 * The template for displaying the footer.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Maxcoach
 * @since   1.0
 */

?>
</div><!-- /.content-wrapper -->

<?php Maxcoach_THA::instance()->footer_before(); ?>

<?php get_template_part( 'template-parts/footer/entry' ); ?>

<?php Maxcoach_THA::instance()->footer_after(); ?>

</div><!-- /.site -->

<?php wp_footer(); ?>
 <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var currentPageUrl = window.location.href;
        var inputElement = document.querySelector("input[name='mf-user']");
        if(inputElement) {
            inputElement.value = currentPageUrl;
        }
    });
    </script>
<script type="text/javascript">
	/*
  // Get the current date
  var currentDate = new Date();

  // Format the date as "d mmm yyyy"
  var formattedDate = currentDate.getDate() + " " + getMonthAbbreviation(currentDate.getMonth()) + " " + currentDate.getFullYear();

  // Set the value of the input field
  document.getElementById("date_joined-6165").value = formattedDate;

  // Helper function to get the abbreviated month name
  function getMonthAbbreviation(monthIndex) {
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[monthIndex];
  }
  */
</script>
<script>
	/*
document.addEventListener('DOMContentLoaded', function() {
    var disabledFields = document.querySelectorAll('div.um-form input[disabled="disabled"], div.um-form textarea[disabled="disabled"]');

    disabledFields.forEach(function(field) {
        field.removeAttribute('disabled');
    });
});

*/
</script>
<script>
	
	function extractSrcFromIframe(inputElement) {
    const inputValue = inputElement.value;

    // Check if the string contains iframe and the src attribute with either single or double quotes
    let srcMatch = inputValue.match(/src="([^"]+)"/) || inputValue.match(/src='([^']+)'/);

    if (srcMatch && srcMatch[1]) {
        inputElement.value = srcMatch[1]; // Update the input field's value to the extracted src value
    }
}

// IDs of the elements we want to attach the event to
const ids = ['osf_googlemap-9067', 'osf_googlemap-8924'];

ids.forEach(id => {
    const element = document.getElementById(id);

    if (element) {
        element.addEventListener('input', function() {
            extractSrcFromIframe(this);
        });
    } else {
        console.warn(`Element with ID ${id} not found.`);
    }
});

</script>

<script>
	/*
    function initAutocomplete() {
    // Get the input field by its id
    var input = document.getElementById('osf_googlemap-8924');
    
    // Create the autocomplete object, restricting the search predictions to geographical location types.
    var autocomplete = new google.maps.places.Autocomplete(input); */
}

// Since the Google Maps script uses async defer, we add the callback parameter to ensure our function is called when the script loads
/*
 * 
 * document.addEventListener("DOMContentLoaded", function() {
  initAutocomplete();
});
autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
        // User entered the name of a Place that was not suggested and pressed the Enter key, or the Place Details request failed.
        window.alert("No details available for input: '" + place.name + "'");
        return;
    }
    // You can capture other details of the place from the 'place' object if needed
});
*/
</script>


<script>
    function initAutocomplete() {
    // Get the input field by its id
    const ids = ['osf_googlemap-9067', 'osf_googlemap-8924'];
    ids.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }
            });
        }
    });

   
    
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-mN78pFCgX22X_B3CMDXhvXmzb-YgZw0&libraries=places&callback=initAutocomplete"></script>
</body>
</html>
