<?php
include 'config.php';

try {
    // Connexion à la base de données 'sportify'
    $conn->exec("USE sportify");
    $tables = $conn->query("SHOW TABLES LIKE 'utilisateurs'");
    // on verifie si des Tbales existent ou pas 
    if ($tables->rowCount() == 0) {
        // on passe a la creation 
        $conn->exec("DROP TABLE IF EXISTS avis, reservations, activites, utilisateurs");
        echo "✅ Tables supprimées avec succès.<br>";

        // Création des tables
        $conn->exec("CREATE TABLE utilisateurs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");

        $conn->exec("CREATE TABLE activites (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom_activite VARCHAR(100) NOT NULL,
            description TEXT,
            prix DECIMAL(10,2),
            duree INT NOT NULL COMMENT 'Durée en minutes',
            max_participants INT NOT NULL,
            niveau INT NOT NULL,
            nom_moniteur VARCHAR(100) NOT NULL, 
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");


        $conn->exec("CREATE TABLE IF NOT EXISTS reservations (
            reservation_id VARCHAR(20), 
            utilisateur_id INT NOT NULL,
            activite_id INT NOT NULL,
            niveau VARCHAR(20),
            prix DECIMAL(10,2),
            date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (reservation_id, activite_id),  
            FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
            FOREIGN KEY (activite_id) REFERENCES activites(id)
        )");

        $conn->exec("CREATE TABLE IF NOT EXISTS devis (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reservation_id VARCHAR(20) NOT NULL,
            user_id INT NOT NULL,
            course_type VARCHAR(20) NOT NULL,
            user_email VARCHAR(100) NOT NULL,
            base_price DECIMAL(10,2) NOT NULL,
            supplements DECIMAL(10,2) NOT NULL,
            total_price DECIMAL(10,2) NOT NULL,
            payment_status VARCHAR(20) DEFAULT 'pending',
            payment_date TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
            FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE
        )");

        $conn->exec("CREATE TABLE avis (
            id INT AUTO_INCREMENT PRIMARY KEY,
            utilisateur_id INT NOT NULL,
            activite_id INT NOT NULL,
            note INT CHECK (note >= 1 AND note <= 5),
            commentaire TEXT,
            date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
            FOREIGN KEY (activite_id) REFERENCES activites(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;");

        echo "✅ Tables créées avec succès.<br>";

        // Insertion des activités sportives
        $activites = [
            [
                'Yoga',
                'Le yoga est une discipline qui allie force, flexibilité et relaxation. Nos cours collectifs d 1h sont ouverts à tous, avec trois niveaux 
                adaptés à vos besoins : débutant, intermédiaire et avancé. Encadrés par Michelle Legrand, ces cours sont limités à 5 participants pour un suivi personnalisé. Ce programme vise à améliorer
                 votre bien-être général, en combinant des postures et des exercices respiratoires pour renforcer votre corps tout en apaisant votre esprit.',
                15.00,
                60,
                5,
                0,
                'Michelle Legrand'
            ],
            [
                'Pilates',
                'Le Pilates est un entraînement doux qui vise à renforcer votre corps en profondeur, améliorer votre posture et augmenter votre flexibilité.
                 Nos cours collectifs d 1h, avec un maximum de 5 participants, sont adaptés à trois niveaux : débutant, intermédiaire et avancé. Sous l expertise de nos coachs, vous développerez votre stabilité, votre équilibre et votre coordination. Chaque session
                 est personnalisée pour répondre à vos objectifs spécifiques, que ce soit pour améliorer votre tonus musculaire ou corriger des déséquilibres.',
                18.00,
                60,
                3,
                0,
                'Marion May'
            ],
            [
                'Renforcement musculaire',
                'Le renforcement musculaire est une méthode efficace pour améliorer votre force, tonifier vos muscles et augmenter votre endurance. Nos séances
                 d 1h, limitées à 5 participants, sont adaptées à trois niveaux : débutant, intermédiaire et avancé. Chaque programme est personnalisé en fonction de vos objectifs, que ce soit pour sculpter votre corps
                 ou améliorer votre performance physique. Vous serez accompagné à chaque étape par nos coachs pour garantir des progrès visibles et durables.',
                12.00,
                45,
                5,
                0,
                'Camille Lemont'
            ],
            [
                'Cycling',
                'Le cycling est un entraînement cardio intense, réalisé sur un vélo stationnaire, qui améliore votre endurance et votre condition physique
                 globale. Nos sessions collectives d 1h sont disponibles à trois niveaux (débutant, intermédiaire, avancé) et sont encadrées par des coachs expérimentés. Avec des séances dynamiques et motivantes, vous pourrez brûler des calories
                 tout en développant votre force et votre endurance. Le programme est ajusté en fonction de vos capacités et de vos objectifs personnels.',
                20.00,
                45,
                3,
                0,
                'Amy Taylor'
            ],
            [
                'Fitness',
                'Le fitness est une activité polyvalente qui combine des exercices de cardio, de renforcement musculaire et de stretching. Nos cours collectifs
                 d 1h sont adaptés à tous les niveaux (débutant, intermédiaire, avancé), et chaque programme est personnalisé selon vos besoins spécifiques. Que vous souhaitiez améliorer votre forme générale,
                 perdre du poids ou tonifier votre corps, nos coachs vous guideront pour atteindre vos objectifs de manière progressive et sécurisée.',
                10.00,
                45,
                5,
                0,
                'Laura Jones'
            ],
            [
                'Programme personnalisé',
                'Notre programme personnalisé est conçu pour répondre à vos objectifs spécifiques, qu il s agisse de perdre du poids, de gagner en force, 
                d améliorer votre flexibilité ou de maintenir une bonne condition physique. Chaque plan est ajusté à votre niveau, vos préférences et votre rythme. Grâce à 
                un suivi régulier, vous serez guidé par nos coachs pour maximiser vos performances et atteindre vos résultats en toute sécurité et durabilité.',
                50.00,
                60,
                1,
                0,
                'Laura Marins'
            ]
        ];

        foreach ($activites as $activite) {
            $sql = "INSERT INTO activites (nom_activite, description, prix, duree, max_participants, niveau, nom_moniteur) 
                    VALUES ('$activite[0]', '$activite[1]', $activite[2], $activite[3], $activite[4], $activite[5], '$activite[6]')";
            $conn->exec($sql);
        }
        echo "✅ Activités ajoutées avec succès.<br>";
    }
} catch (PDOException $e) {
    die("❌ Erreur : " . $e->getMessage());
}
?>