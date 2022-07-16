<div>
    <h4>Application : <?= $app['name']?></h4>
    <h4>Nom domaine : <?= $app['url']?></h4>
    <h4>Si vous acceptez vous serez rediriger vers :  "<?= $app['redirect_uri']?>"</h4>
    <a href="http://localhost:8080/oauth2/auth-success?client_id=<?=$app['client_id']?>">Oui</a>
    <a href="/oauth2/failed">Non</a>
</div>
