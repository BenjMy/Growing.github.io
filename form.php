<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Formulaires</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
   <body>
		<p>
			Filters HTML<br /> <!-- 3 Filters html: Method, Organisation and Scale -->
 		</p>
		
         <form action="MapIndex.php" method="post"> <!-- // 4 - envoyer du POST -->
            <select name="methodology">
                <option value="ERT">ERT</option>
                <option value="IP">IP</option>
            </select>
            <select name="organisation">
                <option value="UNIPD">UNIPD</option>
                <option value="CERTE">CERTE</option>
                <option value="LBL">LBL</option>
            </select>
            <select name="scale">
                <option value="Laboratory">Laboratory</option>
                <option value="Field">Field</option>
            </select>
            <input type="submit" value="Valider" />
        </form>
   </body>
</html>
