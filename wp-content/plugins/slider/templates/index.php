<form method="get" action ="" id="Nzs-hs-form-1">

	Sortuj według
	<select name="orderby">
		<option value="id">ID</option>
		<option value="position">Pozycja</option>
		<option value="published">Widoczność</option>
	</select>

	<select name="orderdir" >
		<option value="asc">Rosnąco</option>
		<option value="desc">Malejąco</option>
	</select>
	<input type="submit" class="button-secoundary" value="Sortuj" />

</form>

<form action="" method="post" id="Nzs-hs-form-2">
	
	<div class="tablenav">

		<div class="alignleft actions">
			<select name="bulkaction" >
				<option value="0">Masowe Działania</option>
				<option value="delete">Publiczny</option>
				<option value="public">Usuń</option>
				<option value="private">prywatny</option>
			</select>		

			<input type="submit" class="button-secoundary" value="Zastosuj">

		</div>
		
		<div class="tablenav-pages">
			<span class="displaying-num"> 4 Slajdy </span>

			<span class="pagination-links">
				<a href="#" title="Idz do pierwszej strony" class="first-page disabled"><←</a>&nbsp;&nbsp;
				<a href="#" title="Idz do poprzedniej strony" class="first-page disabled">←</a>&nbsp;&nbsp;

				<span class="paging-input"> 1 z <span class="total-pages">4</span></span>

				&nbsp;&nbsp;<a href="#" title="Idz do następnej strony" class="next-page">→</a>
				&nbsp;&nbsp;<a href="#" title="Idz do ostatniej strony" class="last-page">→></a>
			</span>
		</div>

	</div>

</form>