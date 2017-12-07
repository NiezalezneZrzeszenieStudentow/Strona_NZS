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
				<option value="public">Usuń</option>
				<option value="delete">Publiczny</option>
				<option value="private">prywatny</option>
			</select>		

			<input type="submit" class="button-secoundary" value="Zastosuj">

		</div>
		
		<div class="tablenav-pages">
			<span class="displaying-num"> 4 Slajdy </span>

			<span class="pagination-links">
				<a href="#" title="Idz do pierwszej strony" class="first-page disabled"><←</a>&nbsp;&nbsp;
				<a href="#" title="Idz do poprzedniej strony" class="prev-page disabled">←</a>&nbsp;&nbsp;

				<span class="paging-input"> 1 z <span class="total-pages">4</span></span>

				&nbsp;&nbsp;<a href="#" title="Idz do następnej strony" class="next-page">→</a>
				&nbsp;&nbsp;<a href="#" title="Idz do ostatniej strony" class="last-page">→></a>
			</span>
		</div>
		<div class="clear"></div>

	</div>
	
	<table class="widefat">
		<thead>
			<tr>
				<th class="check-column"><input type="checkbox"></th>
				<th> ID</th>
				<th>Miniaturka</th>
				<th>Tytuł</th>
				<th>Opis</th>
				<th>Czytaj więcej</th>
				<th>Pozycja</th>
				<th>Widoczny</th>
			</tr>
			<tbody id="the-list">
				<tr>
					<td colspan="8"> Brak slajdów w bazie danych </td>

					<tr>
						<th class="check-column">
							<input type="checkbox" value="1" name="bulkcheck[]" >
						</th>
						<td>1</td>
						<td>
							Podgląd slajdu
							<div class="row-actions">
								<span class="edit">
									<a class="edit" href="#">Edytuj</a>
								</span> |

								<span class="trash">
									<a class="delete" href="#">Usuń</a>
								</span>
							</div>
						</td>
						<td>Slajd 1</td>
						<td>Opis</td>
						<td>www.nzs.pl</td>
						<td>1</td>
						<td>tak</td>
					</tr>
					<tr class="alternate">
						<th class="check-column">
							<input type="checkbox" value="1" name="bulkcheck[]" >
						</th>
						<td>1</td>
						<td>
							Podgląd slajdu
							<div class="row-actions">
								<span class="edit">
									<a class="edit" href="#">Edytuj</a>
								</span> |

								<span class="trash">
									<a class="delete" href="#">Usuń</a>
								</span>
							</div>
						</td>
						<td>Slajd 1</td>
						<td>Opis</td>
						<td>www.nzs.pl</td>
						<td>1</td>
						<td>tak</td>
					</tr>
				</tr>
			</tbody>
		</thead>
	</table>

	<div class="tablenav">
		<div class="tablenav-pages">
			<span class="pagination-links">
				Przejdz do strony
				&nbsp;<strong>1</strong>
				&nbsp;<a href="#">1</a>
				&nbsp;<a href="#">2</a>
			</span>
		</div>

		<div class="clear"></div>
	</div>
</form>