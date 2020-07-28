<?php

		// Javascript arrays
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	

		var kp = new Array();
		var genre = new Array();


	</script>

	<script language="JavaScript" type="text/javascript">

		function init()
		{
			// auch zwei arrays notwendig, da ids auch werte
			var dblen = ursprung.length;
			var selgr = 1;
			for ( i=1; i < dblen; i++ )
			{
				NeuerEintrag = new Option(ursprung[i]);
				document.spielfilm_erfassen.ursprung.options = NeuerEintrag;
				document.spielfilm_erfassen.ursprung.options.text = ursprung[i];
				document.spielfilm_erfassen.ursprung.options.value = ursprung_id[i];
			}

			dblen = genre.length;
			selgr = 1;
			for ( i=1; i < dblen; i++ )
			{
				NeuerEintrag = new Option(genre[i]);
				document.spielfilm_erfassen.genre.options = NeuerEintrag;
				document.spielfilm_erfassen.genre.options.text = genre[i];
				document.spielfilm_erfassen.genre.options.value = genre_id[i];
			}
		}

	</script>
?>