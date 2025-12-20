<?php
	require __DIR__ . "/config.php";

	$search = isset($_GET['keresett']) ? trim($_GET['keresett']) : '';

	if ($search === '') {
		$sql = "SELECT * FROM rooms";
	} else {
		$escaped = $conn->real_escape_string($search);
		$sql = "SELECT * FROM rooms WHERE room_number LIKE '%$escaped%'";
	}

	$founded_room = $conn->query($sql);

	if ($founded_room && $founded_room->num_rows > 0) {
		echo "<table>\n";
		echo "<tr>\n  <th>Szoba</th>\n  <th>Ár</th>\n  <th>Műveletek</th>\n</tr>\n";

		while ($sor = $founded_room->fetch_assoc()) {
			$roomNumber = $sor['room_number'];
			$price = $sor['price_per_night'];

			echo "<tr>\n";
			echo "  <td><a href='/Hotel/Hotel oldala/Kezelo/room.php?id={$sor['id']}'>" . $roomNumber . "</a></td>\n";
			echo "  <td>" . $price . " Ft/éjszaka</td>\n";
			echo "  <td>
						<form method='POST' action='/Hotel/Hotel oldala/Kezelo/index.php'>
							<input type='hidden' name='roomid' value='{$sor['id']}'>
							<input type='submit' name='del-room-btn' value='Törlés'>
						</form>
					</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	} else {
		echo "<p>Nincs megjelenítendő adat.</p>";
	}
