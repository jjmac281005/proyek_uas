<?php
        $cafes = [
            [
                'id' => '123',
                'name' => 'Alligator Cafe',
                'address' => 'Jl. Kenanga No.17, Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara 20132',
                'rating' => '4.5',
                'cuisine' => 'American Cuisine',
                'image' => 'gambar/ALLIGATOR/LOGO.jpeg'
            ],
            [
                'id' => '124',
                'name' => 'Koora Cafe',
                'address' => 'Jl. Sei Besitang No.6, Sei Sikambing D, Kec. Medan Petisah, Kota Medan',
                'rating' => '4.2',
                'cuisine' => 'Korean Cuisine',
                'image' => 'gambar/KOORA/LOGO.png'
            ],
            [
                'id' => '125',
                'name' => 'Omotesando Cafe',
                'address' => 'Jl. Letjen Suprapto No.13, Hamdan, Kec. Medan Maimun, Kota Medan',
                'rating' => '4.8',
                'cuisine' => 'Japanese Cuisine',
                'image' => 'gambar/OMOTESANDO/LOGO.jpeg'
            ],
            [
                'id' => '126',
                'name' => 'Potte Cafe',
                'address' => 'Jl. Dr. Mansyur No.98, Padang Bulan Selayang I, Medan',
                'rating' => '4.3',
                'cuisine' => 'Italian Cuisine',
                'image' => 'gambar/POTTE/LOGO.jpeg'
            ],
            [
                'id' => '127',
                'name' => 'Thirty Six Cafe',
                'address' => 'Jl. Multatuli No.36, Hamdan, Medan Maimun, Kota Medan',
                'rating' => '4.7',
                'cuisine' => 'American Cuisine',
                'image' => 'gambar/THIRTY SIX/LOGO.jpeg'
            ],
        ];

        foreach ($cafes as $cafe) {
            echo '<div class="kafe-box" onclick="goToDetails(\'' . $cafe['id'] . '\')">';
            echo '  <div class="logo-kafe"><img src="' . $cafe['image'] . '" alt="Cafe Image"></div>';
            echo '  <div class="info-kafe">';
            echo '    <div class="kafe-name">' . $cafe['name'] . '</div>';
            echo '    <div class="kafe-address">' . $cafe['address'] . '</div>';
            echo '    <div class="kafe-rating">⭐ ' . $cafe['rating'] . '/5</div>';
            echo '    <div class="kafe-cuisine">Cuisine: ' . $cafe['cuisine'] . '</div>';
            echo '  </div>';
            echo '</div>';
        }
        ?>
