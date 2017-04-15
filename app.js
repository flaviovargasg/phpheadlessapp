
/**
Es necesario modificar esta url y apuntar a la instalación  de wp
Por ejemplo http://localhost/Wordpress
*/

var BASE_URL='http://localhost/wp_peliculas';

var app = {

	init: function() {
		app.getPosts();
	},

	getPosts: function() {

		var rootURL = BASE_URL+'/wp-json/wp/v2';

		$.ajax({
			type: 'GET',
			url: rootURL + '/movie?_jsonp=?', //quité /posts?_jsonp=? y puse movie?_jsonp=? ?orderby=title
			dataType: 'json',
			success: function(data){

				$.each(data, function(index, value) {
					console.log(value);

			      $('ul.post-list').append('<li class="post-list__item">' +
			     		'<img src="'+value.featured_image.attachment_meta.sizes.medium.url+'" /><br>' +
			      	'<h3>'+value.title.rendered+'</h3>' +
			      	'<p>'+value.content.rendered+'</p></li>');
			    });
					 $("ul.post-list").listview("refresh");
			},
			error: function(error){
				console.log(error);
			}

		});

	}

}
