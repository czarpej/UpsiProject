<?php

echo '<script>
		$(window).scroll(function()
			{
				if($(this).scrollTop()>300) $(".scrolltop").stop().fadeIn(); //jeżeli this-ten (wskaźnik) scroll górny jest większy od 300 to złap element klasy scrollup i niech się pojawi 
				else $(".scrolltop").stop().fadeOut(); //w przeciwnym wypadku stopniowo zanikaj w niebyt, wyblaknij
			}
			);
		function scrolltop()
		{
			$("html, body").animate({scrollTop:0}, 300);
		}
		</script>
		<aside>
		<a href="#" class="scrolltop" onclick="scrolltop()"></a>
		</aside>';