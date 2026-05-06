<?php
class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST');
            $db = getenv('DB_DATABASE');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASSWORD');

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass
                );

                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    public static function createDB()
    {

        try {
            $queries = [];

            // usuarios
            $queries[] = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            role ENUM('admin', 'funcionario') DEFAULT 'funcionario',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        ";

            // clientes
            $queries[] = "
        CREATE TABLE IF NOT EXISTS clientes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            cpf VARCHAR(11) UNIQUE NOT NULL,
            telefone VARCHAR(20),
            email VARCHAR(150),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        ";

            // veiculos
            $queries[] = "
        CREATE TABLE IF NOT EXISTS veiculos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_id INT NOT NULL,
            placa VARCHAR(10) NOT NULL UNIQUE,
            modelo VARCHAR(100),
            marca VARCHAR(100),
            cor VARCHAR(50) NOT NULL,
            ano INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
        ";

            // ordens
            $queries[] = "
        CREATE TABLE IF NOT EXISTS ordens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_id INT NOT NULL,
            veiculo_id INT NOT NULL,
            usuario_id INT NOT NULL,
            descricao TEXT,
            status ENUM('aberta', 'diagnostico', 'reparo', 'finalizada', 'cancelada') DEFAULT 'aberta',
            data DATE NOT NULL,
            valor_total DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (cliente_id) REFERENCES clientes(id),
            FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ) ENGINE=InnoDB;
        ";

            // pagamentos
            $queries[] = "
        CREATE TABLE IF NOT EXISTS pagamentos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ordem_id INT NOT NULL,
            valor DECIMAL(10,2) NOT NULL,
            metodo ENUM('dinheiro', 'cartao', 'pix') NOT NULL,
            status ENUM('pendente', 'pago') DEFAULT 'pendente',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (ordem_id) REFERENCES ordens(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
        ";

            foreach ($queries as $sql) {
                $conn = self::getConnection();
                $conn->exec($sql);
            }

            echo "Banco e tabelas criados com sucesso!";

        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}

$host = getenv('DB_HOST');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');
$user = getenv('DB_USER');

$conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $password);