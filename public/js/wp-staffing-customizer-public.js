jQuery( document ).ready( function ( $ ) {
	$( '.job_listings' )

		.on( 'change', '.job-manager-category-dropdown', function() {
			var target = $(this).closest( 'div.job_listings' );
			target.trigger( 'update_results', [ 1, false ] );

			return false;
		})

		.on( 'reset', function() {
			$('.job-manager-category-dropdown').val("");
		});

});