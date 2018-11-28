<div class="hidden" id="city-content">
	<div class="city-item" data-city="">
		<span class="project__index-name">Город</span>
		<span class="city-del">&#10006</span>
		<span class="add-loc-btn">Добавить еще ТТ</span>
		<div class="project__index-row">
			<label class="project__index-lbl">Город</label>
			<div class="city-field project__index-arrow">
				<span class="city-select"></span>
				<input type="text" name="c" class="city-inp" autocomplete="off">
				<ul class="select-list"></ul>
				<input type="hidden" name="city[]" value="">
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="loc-content">
	<div class="loc-item" data-id="0">
		<span class="loc-del">&#10006</span>
		<span class="project__index-name">Локация</span>
		<span class="add-period-btn">Добавить период</span>
		<div class="project__index-row loc-field">
			<div class="project__index-pen lindex">
				<label class="project__index-lbl">Улица</label>
				<input type="text" name="lindex" autocomplete="off">
			</div>
			<div class="project__index-pen lname">
				<label class="project__index-lbl">Название ТТ</label>
				<input type="text" name="lname" autocomplete="off">
			</div>
			<div class="project__index-pen lhouse">
				<label class="project__index-lbl">Дом</label>
				<input type="text" name="lhouse" autocomplete="off" data-checker="house">
			</div>
			<div class="project__index-pen lbuilding">
				<label class="project__index-lbl">Здание</label>
				<input type="text" name="lbuilding" autocomplete="off" data-checker="house">
			</div>
			<div class="project__index-pen lconstruction">
				<label class="project__index-lbl">Строение</label>
				<input type="text" name="lconstruction" autocomplete="off" data-checker="house">
			</div>
			<div class="project__index-pen lcorps">
				<label class="project__index-lbl">Корпус</label>
				<input type="text" name="lcorps" autocomplete="off" data-checker="house">
			</div>
			<div class="project__index-row comment">
				<label class="project__index-lbl">Комментарий</label>
				<textarea name="comment"></textarea>
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="period-content">
	<div class="period-item" data-id="0">
		<span class="period-del">&#10006</span>
		<span class="project__index-name">Период</span>
		<div class="post-item">
			<label class="project__index-lbl">Должность</label>
			<div class="post-field project__index-arrow">
				<span class="post-select"></span>
				<input type="text" name="p" class="post-inp" autocomplete="off">
				<ul class="select-list">
					<li data-id="0" class="emp">Список пуст</li>
					<? foreach ($arPosts as $key => $post): ?>
						<li data-id="<?=$key?>"><?=$post?></li>
					<? endforeach; ?>
				</ul>
				<input type="hidden" name="post" value="">
			</div>
		</div>
		<div class="period-field">
			<label class="project__index-lbl">Дата</label>
			<span></span>
			<div class="calendar" data-type="bdate">
				<table>
					<thead>
					<tr>
						<td class="mleft">‹
						<td colspan="5" class="mname">
						<td class="mright">›
					</tr>
					<tr>
						<td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
					</tr>
					<tbody></tbody>
				</table>
			</div>
			<input type="hidden" name="bdate">
		</div>
		<div class="period-field">
			<label class="project__index-lbl">по</label>
			<span></span>
			<div class="calendar" data-type="edate">
				<table>
					<thead>
					<tr>
						<td class="mleft">‹
						<td colspan="5" class="mname">
						<td class="mright">›
					</tr>
					<tr>
						<td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
					</tr>
					<tbody></tbody>
				</table>
			</div>
			<input type="hidden" name="edate">
		</div>
		<div class="project__index-pen time-item">
			<label class="project__index-lbl">Время работы</label>
			<input type="text" name="btime" class="time-inp">
		</div>
		<div class="project__index-pen time-item">
			<label class="project__index-lbl">по</label>
			<input type="text" name="etime" class="time-inp">
		</div>
	</div>
</div>
<div class="hidden" id="metro-content">
	<div class="metro-item">
		<label class="project__index-lbl">Метро</label>
		<div class="metro-field project__index-arrow">
			<span class="metro-select"></span>
			<input data-checker="metro" type="text" name="m" class="metro-inp" autocomplete="off">
			<ul class="select-list"></ul>
			<input data-checker="metro" type="hidden" name="metro" value="">
		</div>
	</div>
</div>