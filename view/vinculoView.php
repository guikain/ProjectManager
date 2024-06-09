<?php

namespace App\view;
use DateTime;
use App\dal\ProjetoDao;
use App\dal\UsuarioDao;

class VinculoView
{

    public static function listar($vinculos, $msg = null, $config = []){
        ?><h1 class = "container">Meus Projetos</h1><?php
        if (isset($msg)) : ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        <table>
            <tr>
                <th>Nome</th>
                <th>Data de Inicio</th>
                <th>Prazo</th>
                <th>Data de Conclusão</th>
                <th>Status</th>
                <th>Descrição</th>
                <th>Tempo Dedicado</th>
                <?php if (isset($config['isLoggedIn']) && $config['isLoggedIn']) : ?>
                    <th>Ação</th>
                <?php endif; ?>
            </tr>   
            <?php foreach ($vinculos as $vinculo) :?>
                <tr>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('nome') ?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('data_inicio') ?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('prazo') ?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('data_fim') ?></td>
                    <td><?= $vinculo->__get("status") ?></td>
                    <td><?= $vinculo->__get("descricao") ?></td>
                    <td> <?=$vinculo->__get("totalTime")?></td>
                    <?php if (isset($config['isLoggedIn']) && $config['isLoggedIn'] && $vinculo->__get("joined") == true ): ?>
                        <td><a href='?p=leave&id=<?= $vinculo->__get('id') ?? '' ?>'>Sair</a></td>
                    <?php elseif (isset($config['isLoggedIn']) && $config['isLoggedIn'] && $vinculo->__get("joined") == false ) : ?>
                        <td><a href='?p=join&id=<?= $vinculo->__get('id') ?? '' ?>'>Entrar</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table><?php if (empty($vinculos)):?>
                <br><div class="erro">
                <?= $msg = 'Você não possui nenhum projeto.'; ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <?php endif;?>
        <?php
    }

    public static function checkout($vinculo = null, $msg = null, $Erro = null){
        ?><h1 class = "container">Descreva suas atividades</h1><?php
        if (isset($msg)):
            if (isset($Erro)): ?>
                <div class="erro"> 
            <?php else: ?>
                <div class="sucesso">
            <?php endif; ?>
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
                </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>

        <form action="?p=leave" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $vinculo ? $vinculo->__get('id') : '' ?>">
            <input required type="hidden" name="idp" value="<?= $vinculo ? $vinculo->__get('id_projeto_fk') : '' ?>">

            <label for="status">Status: </label>
            <input required type="text" name="status" id="status" value="<?= $vinculo ? $vinculo->__get('status') : '' ?>">

            <label for="descricao">Descrição: </label>
            <input required type="text" name="descricao" id="descricao" value="<?= $vinculo ? $vinculo->__get('descricao') : '' ?>">

            <button type="submit">Salvar</button>
        </form><?php
    }

    public static function alterar($msg = null, $vinculo = null, $erro = false) {
        ?><h1 class = "container">Dados editaveis do vínculo</h1><?php
        if ($msg) : ?>
            <div class="<?= $erro ? 'erro' : 'sucesso' ?>">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        
        <ul>
            <li>Vinculo: <?= $vinculo->__get('id')?></li>
            <li>Nome do projeto: <?= ProjetoDao::buscar($vinculo->__get('id_projeto_fk'))->__get('nome')?></li>
            <li>Nome do Usuário: <?= UsuarioDao::buscar($vinculo->__get('id_user_fk'))->__get('nome')?></li>
            
        </ul>
        <form action="?p=altvinculo" method="post">
            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $vinculo ? $vinculo->__get('id') : '' ?>">
            
            <label for="descricao">Descrição: </label>
            <input type="text" name="descricao" id="descricao" value="<?= $vinculo ? $vinculo->__get('descricao') : '' ?>">
    
            <label for="isjoined">Em Aberto: </label>
            <input type="checkbox" name="isjoined" id="isjoined" value="1" <?= $vinculo && $vinculo->__get('joined') ? 'checked' : '' ?>>
            
            <button type="submit">Salvar</button>
        </form><?php
    }

    public static function listarTudo($vinculos, $msg = null, $config = []){
        ?><h1 class = "container">Ultimos 30 vinculos</h1><?php
        if (isset($msg)) : ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data de Inicio</th>
                <th>Prazo</th>
                <th>Data de Conclusão</th>
                <th>Status</th>
                <th>Descrição</th>
                <th>Tempo Dedicado</th>
                <th>Atividade</th>
                <th>Ação</th>
                <th>Ação</th>
            </tr>   
            <?php foreach ($vinculos as $vinculo) :?>
                <tr>
                    <td><?= $vinculo->__get("id")?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('nome') ?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('data_inicio') ?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('prazo') ?></td>
                    <td><?= ProjetoDao::buscar($vinculo->__get("id_projeto_fk"))->__get('data_fim') ?></td>
                    <td><?= $vinculo->__get("status") ?></td>
                    <td><?= $vinculo->__get("descricao") ?></td>
                    <td> <?= date('H:i:s', $vinculo->__get("totalTime"))?></td>
                    <td><?= (($vinculo->__get("joined"))? "Aberto": "Fechado") ?></td>
                    <?php if (isset($config['isAdmin']) && $config['isAdmin']) : ?>
                        <td><a href='?p=altvinculo&alt=<?= $vinculo->__get("id") ?>'>Alterar</a></td>
                        <td><a href='?p=delvinculo&del=<?= $vinculo->__get("id") ?>'>Excluir</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table><?php if (empty($vinculos)):?>
                <br><div class="erro">
                <?= $msg = 'DB não possui nenhum vinculo.'; ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <?php endif;?>
        <?php
    }
}
