<?php

use Aerys\Router;
use Aerys\Request;
use Aerys\Response;
use function Aerys\parseBody;

$router = new Aerys\Router();

$router->route("GET", "/", function (Request $request, Response $response) {
    $response->write("<h1>Aerys works</h1>");
    
    $response->write("
        <p>
            submit the form to see if MySQL works
            <form method='POST'>
                Server: <input name='server' value='127.0.0.1' required /><br>
                Username: <input name='username' value='root' required /><br>
                Password: <input name='password' value='' /><br>
                Database: <input name='database' value='sys' required /><br>
                <input type='submit' value='test database' required />
            </form>
        </p>
    ");

    $response->end();
});

$router->route("POST", "/", function (Request $request, Response $response) {
    $response->write("<h1>Aerys works</h1>");

    try {
        $parsed = yield parseBody($request);
        $fields = $parsed->getAll()["fields"];

        $server = $fields["server"][0];
        $username = $fields["username"][0];
        $password = $fields["password"][0];
        $database = $fields["database"][0];

        $pool = Amp\Mysql\pool("host={$server};user={$username};password={$password};db={$database}");

        $statement = yield $pool->prepare("SELECT NOW() as time");
        $result = yield $statement->execute([1337]);
    
        while (yield $result->advance()) {
            $row = $result->getCurrent();
        }
        
        if (isset($row["time"])) {
            $response->write("<h2>So does MySQL...</h2>");
        }
    } catch (Exception $e) {
        $response->write("<h2>Error: " . $e->getMessage() . "</h2>");
    }
    
    $response->end();
});

$root = new Aerys\Root(__DIR__ . "/public");

return (new Aerys\Host)
    ->expose("*", 8888)
    ->use($router)
    ->use($root);
