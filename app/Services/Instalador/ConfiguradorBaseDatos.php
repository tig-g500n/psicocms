<?php

namespace App\Services\Instalador;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use RuntimeException;

class ConfiguradorBaseDatos
{
    public function probarConexion(array $datos): void
    {
        // Primero probamos conectando directamente a la BD (caso hosting compartido: ya existe).
        try {
            $this->pdoConBaseDatos($datos);

            return;
        } catch (PDOException $e) {
            // Puede que la BD aún no exista (caso local): probamos solo el servidor.
        }

        try {
            $this->pdoSinBaseDatos($datos);
        } catch (PDOException $e) {
            throw new RuntimeException('No se pudo conectar al servidor MySQL. Revisa los datos e inténtalo de nuevo.');
        }
    }

    public function crearBaseDatos(array $datos): void
    {
        // En hosting compartido (p. ej. InfinityFree) la BD ya existe y el usuario
        // no tiene privilegio CREATE DATABASE: en ese caso basta con que se pueda conectar.
        try {
            $pdo = $this->pdoSinBaseDatos($datos);
            $nombre = str_replace('`', '', $datos['database']);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$nombre}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            try {
                $this->pdoConBaseDatos($datos);
            } catch (PDOException $e2) {
                throw new RuntimeException('No se pudo crear ni acceder a la base de datos. En hosting compartido, créala antes en el panel y comprueba los datos. Detalle: '.$e->getMessage());
            }
            // La base de datos ya existe y es accesible: continuamos.
        }
    }

    public function aplicarEnRuntime(array $datos): void
    {
        config([
            'database.connections.mysql.host' => $datos['host'],
            'database.connections.mysql.port' => $datos['port'],
            'database.connections.mysql.database' => $datos['database'],
            'database.connections.mysql.username' => $datos['username'],
            'database.connections.mysql.password' => $datos['password'] ?? '',
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function escribirEnv(array $datos): void
    {
        $ruta = base_path('.env');
        $contenido = file_get_contents($ruta);

        $reemplazos = [
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $datos['host'],
            'DB_PORT' => $datos['port'],
            'DB_DATABASE' => $datos['database'],
            'DB_USERNAME' => $datos['username'],
            'DB_PASSWORD' => $datos['password'] ?? '',
        ];

        foreach ($reemplazos as $clave => $valor) {
            $valor = $this->formatearValorEnv($valor);
            $patron = "/^{$clave}=.*$/m";
            if (preg_match($patron, $contenido)) {
                $contenido = preg_replace($patron, "{$clave}={$valor}", $contenido);
            } else {
                $contenido .= PHP_EOL."{$clave}={$valor}";
            }
        }

        file_put_contents($ruta, $contenido);
    }

    public function migrar(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);
    }

    private function pdoSinBaseDatos(array $datos): PDO
    {
        $dsn = "mysql:host={$datos['host']};port={$datos['port']}";

        return new PDO($dsn, $datos['username'], $datos['password'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
    }

    private function pdoConBaseDatos(array $datos): PDO
    {
        $dsn = "mysql:host={$datos['host']};port={$datos['port']};dbname={$datos['database']}";

        return new PDO($dsn, $datos['username'], $datos['password'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
    }

    private function formatearValorEnv($valor): string
    {
        $valor = (string) $valor;

        if ($valor === '') {
            return '';
        }

        if (preg_match('/\s|#|"|\'/', $valor)) {
            return '"'.addslashes($valor).'"';
        }

        return $valor;
    }
}
