<h1><?=$title?></h1>
 <table >
	<tr>
        <th>Idnr</th>
        <th>Tabellnamn:</th>
        <th>Antal fält:</th>
       	<th>Skapad:</th>
    </tr>

<?php foreach ($tabell as $tabl) {
	$properties = $tabl->getProperties();

	$url = $this->url->create('Dbtables/id/' . $properties['id']);
	$url1 = $this->url->create('newtable');
	$url2 = $this->url->create('Dbtables/id/' . $properties['id']);
	echo <<<EOD
	<tr>
		 <td> {$properties['id']}</td>
		 <td> {$properties['tablename']}</td>
		 <td> {$properties['fields']}</td>
		 <td> {$properties['created']}</td>
		 <td><form  action="$url" method="get"><button>Ändra namn och fältantal</button></form></td>
		 <td><form  action="$url1" method="get"><button>Skapa tabellen och fältnamn</button></form></td>
		 <td><form  action="$url2" method="get"><button>Visa tabellen</button></form></td>
     </tr> 
EOD;

}
?>

</table>

 <form style="display: inline" action='<?=$this->url->create('Dbtables/add')?>' method="get">
 	 <button>Lägg till en ny tabell</button></form>
 
 <form style="display: inline" action='<?=$this->url->create('Dbtables/list')?>' method="get">
 	 <button>Åter till Visa Alla</button></form> 
