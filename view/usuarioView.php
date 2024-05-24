<?php
namespace App\view;

class UsuarioView{

    public static function cadastrar($msg = null){
        if (isset($msg)): ?>
        <div class="sucesso">
            <?= $msg ?>
            <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
        </div>
        <?php endif; 
        if (isset($_GET['epw'])): ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['ipw'])): ?>
            <div class="erro">
                <?= 'A senha requer pelo menos 8 caracteres' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        
            <form action="?p=cadusr" method="post">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" required>

            <label for="sobrenome">Sobrenome: </label>
            <input type="text" name="sobrenome" id="sobrenome" required>

            <label for="data_nascimento">Data de Nascimento: </label>
            <input type="date" name="data_nascimento" id="data_nascimento" required>

            <label for="cpf">CPF: </label>
            <input type="text" name="cpf" id="cpf" maxlength="11" required> 

            <label for="ddd">DDD: </label>
            <input type="text" name="ddd" id="ddd" maxlength="2" required>

            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone" maxlength="10" required>

            <label for="username">Usuario: </label>
            <input type="text" name="username" id="username" maxlength="30" required>

            <label for="pass">Senha: </label>
            <input type="password" name="pass" id="pass" maxlength="30" required>


            <button type="submit">Salvar</button>
            </form>
        <?php
    }
    public static function profile($usuario = null, $msg = null) {
        if (isset($msg)): ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; 
        if (isset($_GET['epw'])): ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        
        <form action="?p=profile" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $usuario ? $usuario->__get('id') : '' ?>">

            <label for="nome">Nome: </label>
            <input required type="text" name="nome" id="nome" value="<?= $usuario ? $usuario->__get('nome') : '' ?>">
    
            <label for="sobrenome">Sobrenome: </label>
            <input required type="text" name="sobrenome" id="sobrenome" value="<?= $usuario ? $usuario->__get('sobrenome') : '' ?>">
    
            <label for="data_nascimento">Data de Nascimento: </label>
            <input required type="date" name="data_nascimento" id="data_nascimento" value="<?= $usuario ? $usuario->__get('data_nascimento') : '' ?>">
    
            <label for="cpf">CPF: </label>
            <input required type="text" name="cpf" id="cpf" maxlength="11" value="<?= $usuario ? $usuario->__get('cpf') : '' ?>">
    
            <label for="ddd">DDD: </label>
            <input required type="text" name="ddd" id="ddd" maxlength="2" value="<?= $usuario ? $usuario->__get('ddd') : '' ?>">
    
            <label for="telefone">Telefone: </label>
            <input required type="text" name="telefone" id="telefone" maxlength="10" value="<?= $usuario ? $usuario->__get('telefone') : '' ?>">
    
            <label for="username">Usuário: </label>
            <input required type="text" name="username" id="username" maxlength="30" value="<?= $usuario ? $usuario->__get('username') : '' ?>">
            
            <label for="pass">Senha: </label>
            <input required type="password" name="pass" id="pass" maxlength="30" placeholder="Insira sua senha">
    
            <button type="submit">Salvar</button>
        </form>
        <?php
    }
    
}