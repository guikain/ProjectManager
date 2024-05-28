<?php

namespace App\view;

use App\dal\ProjetoDao;

class ProjetoView
{

    public static function listar($projetos, $msg = null, $config = []){
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
                <th>Prioridade: 1 a 5</th>
                <th>Dificuldade: 1 a 5</th>
                <th>Data de Inicio</th>
                <th>Prazo</th>
                <th>Data de Conclusão</th>
                <th>Status</th>
                <?php if (isset($config['isLoggedIn']) && $config['isLoggedIn']) : ?>
                    <th>Ação</th>
                <?php endif; ?>
                <?php if (isset($config['isAdmin']) && $config['isAdmin']) : ?>
                    <th>Ação</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($projetos as $projeto) : ?>
                <tr>
                    <td><?= $projeto->__get("nome") ?></td>
                    <td><?= $projeto->__get("prioridade") ?></td>
                    <td><?= $projeto->__get("dificuldade") ?></td>
                    <td><?= $projeto->__get("data_inicio") ?></td>
                    <td><?= $projeto->__get("prazo") ?></td>
                    <td><?= $projeto->__get("data_fim") ?></td>
                    <td><?= $projeto->__get("status") ?></td>
                    <?php if (isset($config['isAdmin']) && $config['isAdmin']) : ?>
                        <td><a href='?p=altproj&alt=<?= $projeto->__get("id") ?>'>Alterar</a></td>
                        <td><a href='?p=delproj&del=<?= $projeto->__get("id") ?>'>Excluir</a></td>
                    <?php elseif (isset($config['isLoggedIn']) && $config['isLoggedIn'] && !$config['isJoined']) : ?>
                        <td><a href='?p=join&id=<?= $projeto->__get('id') ?? '' ?>'>Entrar</a></td>
                    <?php elseif (isset($config['isLoggedIn']) && $config['isLoggedIn'] && $config['isJoined']) : ?>
                        <td><a href='?p=leave&id=<?= $projeto->__get('id') ?? '' ?>'>Sair</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

    public static function cadastrar($msg = null){
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
        <?php if (isset($_GET['ipw'])) : ?>
            <div class="erro">
                <?= 'A senha requer pelo menos 8 caracteres' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>

        <form action="?p=cadproj" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="nome">Nome: </label>
            <input required type="text" name="nome" id="nome">

            <label for="prioridade">Prioridade: </label>
            <input required type="text" name="prioridade" id="prioridade">

            <label for="dificuldade">Dificuldade: </label>
            <input required type="text" name="dificuldade" id="dificuldade">

            <label for="prazo">Prazo: </label>
            <input required type="date" name="prazo" id="prazo">

            <label for="status">Status: </label>
            <input required type="text" name="status" id="status" maxlength="30">

            <button type="submit">Salvar</button>
        </form>
        <?php
    }

    public static function alterar($projeto = null, $msg = null){
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

        <form action="?p=altproj" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $projeto ? $projeto->__get('id') : '' ?>">

            <label for="nome">Nome: </label>
            <input required type="text" name="nome" id="nome" value="<?= $projeto ? $projeto->__get('nome') : '' ?>">

            <label for="prioridade">Prioridade: </label>
            <input required type="text" name="prioridade" id="prioridade" value="<?= $projeto ? $projeto->__get('prioridade') : '' ?>">

            <label for="dificuldade">Dificuldade: </label>
            <input required type="text" name="dificuldade" id="dificuldade" value="<?= $projeto ? $projeto->__get('dificuldade') : '' ?>">

            <label for="data_inicio">Data de Início: </label>
            <input required type="date" name="data_inicio" id="data_inicio" value="<?= $projeto ? $projeto->__get('data_inicio') : '' ?>">

            <label for="prazo">Prazo: </label>
            <input required type="date" name="prazo" id="prazo" value="<?= $projeto ? $projeto->__get('prazo') : '' ?>">

            <label for="data_fim">Data de Conclusão: </label>
            <input required type="text" name="data_fim" id="data_fim" value="<?= $projeto ? $projeto->__get('data_fim') : '' ?>">

            <label for="status">Status: </label>
            <input required type="text" name="status" id="status" maxlength="30" value="<?= $projeto ? $projeto->__get('status') : '' ?>">

            <button type="submit">Salvar</button>
        </form><?php
    }
}
