<template>
  <div class="container">
    <h1>{{ msg }}</h1>
    <form class="" v-on:submit.prevent="cadastrar()">
        <div class="form-row">
            <div class="form-group col-md-10">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" v-model="form.nome">
            </div>
            <div class="form-group col-md-2">
            <label for="uf">UF</label>
            <input type="text" class="form-control" id="uf" maxlength="2" v-model="form.uf" v-on:keyup="validate">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>

    <table class="table" align="center" style="margin-top:20px">
        <thead class="thead-dark ">
            <tr>
                <th>ID</th>
                <th>ESTADO</th>
                <th>UF</th>
                <th>CADASTRADO</th>
                <th>ULTIMA ALTERAÇÃO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="estado in estados" :key="estado">
            <td>{{estado._id}}</td>
            <td>{{estado.nome}}</td>
            <td>{{estado.uf}}</td>
            <td>{{estado.data_cadastro}}</td>
            <td>{{estado.data_alteracao}}</td>
            <td><a href="#" @click="alterar(estado._id)">Editar</a> <a href="#" @click="deletar(estado._id)">Deletar</a></td>    
        </tr>
        </tbody>
    </table>

     
      <transition name="modal" v-if="showModal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">    
                <button class="btn btn-danger" @click="showModal = false" style="margin-right:10px">Fechar</button>
                <button type="submit" class="btn btn-primary" @click="alterar()">Enviar</button>
            </div>
          </div>
        </div>
      </transition>
        
  </div>
</template>

<script>
const axios = require('axios');

export default {
    name: 'Estado',
    props: {
        msg: String
    },
    data () {
        return {
            showModal: false,
            estados: null,
            form:{
                id: null,
                nome : null,
                uf: null
            },
        }
    },
    methods:{
        async listar(){
            this.estados = await axios.get('http://localhost/rest/estado/listar/').then(response => (this.estados = response.data.Estado));
        },
       
        Modal(estado){
            this.form.id = estado._id;
            this.showModal = true;
        },
        cadastrar(){
            axios.post('http://localhost/rest/estado/cadastrar', {
                nome: this.form.nome,
                uf: this.form.uf
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
            this.listar();
        },
        alterar(id){
            axios.put('http://localhost/rest/estado/alterar/'+id, {
                nome: this.form.nome,
                uf: this.form.uf
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
            //alert('Alterado com Sucesso');
            this.listar();
        },
        deletar(id){
            axios.delete('http://localhost/rest/estado/deletar/'+id);
            //.then(response => (console.log(response)));
            alert('Deletado com Sucesso');
            this.listar();
        },
        validate() {
            this.form.uf = this.form.uf.replace(/[^a-zA-Z@]+/, '');
        },
    },

    async created () {
        await this.listar()
    },
    watch: {
        // everytime a route is changed refresh the activeUser
        '$route': 'listar'
    },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
