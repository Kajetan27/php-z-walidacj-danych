<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <title>Zadanie z rozszerzoną walidacją</title>
</head>
<body>
    <h1>ZADANIE</h1>
    <form action="index.php" method="post">
        Imię: <input type="text" name="imie" required><br><br>
        Nazwisko: <input type="text" name="nazwisko" required><br><br>
        Wiek: <input type="number" name="wiek" required><br><br>
        Szkoła: <input type="text" name="szkola" required><br><br>
        Liczba osób krótkowłosych: <input type="number" name="krotkowlose" value="0"><br><br>
        Liczba osób długowłosych: <input type="number" name="dlugowlose" value="0"><br><br>
        <input type="submit" value="Wyślij dane">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Pobieranie danych i wstępne czyszczenie
    $imie = trim(htmlspecialchars($_POST['imie']));
    $nazwisko = trim(htmlspecialchars($_POST['nazwisko']));
    $szkola = trim(htmlspecialchars($_POST['szkola']));
    $wiek = (int)$_POST['wiek'];
    $krotkowlose = (int)$_POST['krotkowlose'];
    $dlugowlose = (int)$_POST['dlugowlose'];

    $bledy = [];

    // --- WALIDACJA ---
    if (!preg_match("/^[a-zA-Z\x{00A1}-\x{017F}]+$/u", $imie)) {
        $bledy[] = "Imię powinno zawierać tylko litery.";
    }

    if (!preg_match("/^[a-zA-Z\x{00A1}-\x{017F}]+$/u", $nazwisko)) {
        $bledy[] = "Nazwisko powinno zawierać tylko litery.";
    }

    if (mb_convert_case($imie, MB_CASE_TITLE, "UTF-8") !== $imie) {
        $bledy[] = "Imię powinno zaczynać się z wielkiej litery.";
    }

    if ($wiek < 7 || $wiek > 25) {
        $bledy[] = "Wiek ucznia musi mieścić się w przedziale 7-25 lat.";
    }

    if ($krotkowlose < 0 || $dlugowlose < 0) {
        $bledy[] = "Liczba osób nie może być wartością ujemną.";
    }

    // --- WYNIK ---
    if (empty($bledy)) {
        $osob_w_klasie = $krotkowlose + $dlugowlose;
        echo "<h2>Wprowadzone dane:</h2>";
        // Tutaj dodałem informacje o liczbie osób krótkowłosych i długowłosych
        echo "<p>Uczeń <strong>$imie $nazwisko</strong> ($wiek lat) ze szkoły <strong>$szkola</strong>.</p>";
        echo "<p>W jego klasie jest <strong>$krotkowlose</strong> osób krótkowłosych oraz <strong>$dlugowlose</strong> osób długowłosych.</p>";
        echo "<p><strong>RAZEM W KLASIE: $osob_w_klasie</strong></p>";
    } else {
        echo "<div style='color:red; border:1px solid red; padding: 10px; margin-top: 10px;'>";
        echo "<strong>Wystąpiły błędy:</strong><ul>";
        foreach ($bledy as $blad) {
            echo "<li>$blad</li>";
        }
        echo "</ul></div>";
    }
}
?>
</body>
</html>
