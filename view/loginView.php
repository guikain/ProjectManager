<?php
namespace App\view;

class LoginView{

    public static function login($msg = null){
        if (isset($msg)): ?>
            <div class="erro">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
            </div>
        <?php endif; ?>
            <div class="login-container">
                <form action="?p=login" method="post">
                    <label for="username">Usuário:</label>
                    <input type="text" name="username" id="username" required>
                    
                    <label for="password">Senha:</label>
                    <input type="password" name="password" id="password" required>
                    
                    <button type="submit">Entrar</button>
                    <a href="?p=cadusr">Cadastre-se!</a>
                    <a href="?p=recusr">Esqueceu a senha?</a>
                </form>
            </div>
            <?php
    }
}