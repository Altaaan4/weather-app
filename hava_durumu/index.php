<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Hava Durumu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Buton stilini ayarla */
        button[type="submit"] {
            padding: 15px 30px; /* Buton boyutunu biraz büyüt */
            background-color: transparent; /* Buton arka planını şeffaf yap */
            color: black; /* Yazı rengini siyah yap */
            border: 2px solid black; /* Kenar çizgisini ekle */
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px; /* Butonu aşağıya taşı */
            font-size: 16px; /* Yazı boyutunu büyüt */
        }

        button[type="submit"]:hover {
            background-color: #333; /* Buton rengini hover durumunda koyulaştır */
            color: white; /* Yazı rengini beyaz yap */
        }

        /* Hava durumu bilgilerinin stilini ayarla */
        .weather-info {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="app">
    <div class="header">
        <h1>Hava Durumunu Giriniz...</h1>
        <form method="post">
            <input type="text" name="cityName" placeholder="Sehir...">
            <button type="submit">Gönder</button> <!-- Butonu formun içine al -->
        </form>
    </div>
    <div class="weather-results">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST["cityName"])) {
                $cityName = urlencode($_POST["cityName"]);
                $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $cityName . '&appid=3f40c2955752bab42ef9f84088cae0af&units=metric&lang=tr';
                
                $weather = file_get_contents($url);
                $result = json_decode($weather, true);

                if ($result && $result['cod'] == 200) {
                    $city = $result['name'] . ', ' . $result['sys']['country'];
                    $date = date('l j F Y');
                    $hour = date('H:i', time());
                    $temp = round($result['main']['temp']);
                    $desc = $result['weather'][0]['description'];
                    $minTemp = round($result['main']['temp_min']);
                    $maxTemp = round($result['main']['temp_max']);
                    $humidity = $result['main']['humidity'];
                    $wind = $result['wind']['speed'];

                    // Hava durumu mesajını belirle
                    if ($temp < 0) {
                        $weatherMessage = "Bugün hava çok soğuk, dışarıya çıkarken sıcak tutun!";
                    } elseif ($temp >= 0 && $temp <= 15) {
                        $weatherMessage = "Bugün hava soğuk, kalın giysiler giyin!";
                    } elseif ($temp > 15 && $temp <= 25) {
                        $weatherMessage = "Bugün hava ılık, hafif giysiler yeterli olabilir.";
                    } elseif ($temp > 25 && $temp <= 35) {
                        $weatherMessage = "Bugün hava sıcak, bol su için ve hafif giysiler giyin!";
                    } else {
                        $weatherMessage = "Bugün hava çok sıcak, dışarıda çok fazla vakit geçirmemeye çalışın!";
                    }

                    // Sonuçları HTML olarak ekrana bas
                    echo "
                        <div class='weather-info'>
                            <div class='city'>$city</div>
                            <div class='date'>$date</div>
                            <div class='hour'>$hour</div>
                            <div class='temp'>$temp °C</div>
                            <div class='description'>$desc</div>
                            <div class='temprange'>$minTemp °C / $maxTemp °C</div>
                            <div class='humidity'>Nem : $humidity%</div>
                            <div class='wind'>Rüzgar : $wind m/s N</div>
                            <div class='message'>$weatherMessage</div>
                        </div>
                    ";
                } else {
                    echo "Şehir bulunamadı.";
                }
            } else {
                echo "Lütfen bir şehir adı girin.";
            }
        }
        ?>
    </div>
    <!-- Diğer HTML içeriğiniz buraya gelir -->
</div>

</body>
</html>
