<form action="" method="post" id="nzs-hs-slide-form">

	<table class = "form-table">

		<tbody>
			<tr class="form-field">
				<th>
					<label for="nzs-hs-slide-url">Slide:</label>
				</th>
				<td>
					<a class="button-secondary" id="select-slide-btn">Wybierz Slajd z Bibioletki</a>
					<input type="hidden" name="entry[slide_url]" id="nzs-hs-slide-url" >
					<p class="description">To pole jest wymagane</p>
					<p id="slide-preview"></p>
				</td>
			</tr>

			<tr class="form-field">
				<th>
					<label for="nzs-hs-title">Tytuł:</label>
				</th>
				<td>
					<input type="text" name="entry[title]" id="nzs-hs-title" >
					<p class="description">To pole jest wymagane</p>
					
				</td>
			</tr>
			<tr class="form-field">
				<th>
					<label for="nzs-hs-caption">Podpis:</label>
				</th>
				<td>
					<input type="text" name="entry[caption]" id="nzs-hs-caption" >
					<p class="description">To pole jest opcjonalne</p>
				</td>
			</tr>

			<tr class="form-field">
				<th>
					<label for="nzs-hs-read-more-url">Link "czytaj wiecej:"</label>
				</th>
				<td>
					<input type="text" name="entry[read_more_url]" id="nzs-hs-read-more-url" >
					<p class="description">To pole jest opcjonalne</p>
					
				</td>
			</tr>

			<tr >
				<th>
					<label for="nzs-hs-position">Pozycja:</label>
				</th>
				<td>
					<input type="text" name="entry[position]" id="nzs-hs-position" >
					<a class="button secondary" id="get-last-pos">Pobierz ostatiną wolną pozycje</a>
					<p id="pos-info" class="description">To pole jest wymagane</p>
					
				</td>
			</tr>

			<tr >
				<th>
					<label for="nzs-hs-published">Link "czytaj wiecej:"</label>
				</th>
				<td>
					<input type="checkbox" name="entry[published]" id="nzs-hs-published" >
					
				</td>
			</tr>

		</tbody>

	</table>

	<p class="submit">
		<a href="#" class="button-secondary">Wstecz</a>
		&nbsp;
		<input type="submit" class="button-primary" vlaue="Zapisz zmiany">
	</p>
</form>