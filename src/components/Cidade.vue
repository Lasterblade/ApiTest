<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
    
    <form class="container">

   
    <div class="form-row">
        <div class="form-group col-md-8">
        <label for="inputCity">Nome</label>
        <input type="text" class="form-control" id="inputCity" v-model="form.nome">
        </div>
        <div class="form-group col-md-4">
        <label for="inputState">Estado</label>
        <select id="inputState" class="form-control" v-model="form.estadoId">
            <option selected>Escolha...</option>
            <option v-for="estado in estados" :key="estado.nome" :value="estado._id" >{{estado.nome}} - {{estado.uf}}</option>
        </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary" @click="cadastrar()">Enviar</button>
    </form>

    <table class="table" align="center" style="margin-top:20px">
        <thead class="">
            <tr>
                <th>ID</th>
                <th>CIDADE</th>
                <th>ESTADO</th>
                <th>CADASTRADO</th>
                <th>ULTIMA ALTERAÇÃO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="cidade in cidades" :key="cidade._id">
            <td>{{cidade._id}}</td>
            <td>{{cidade.nome}}</td>
            <td>{{filtrar(cidade.estadoId)}}</td>
            <td>{{cidade.data_cadastro}}</td>
            <td>{{cidade.data_alteracao}}</td>
            <td><a href="#" @click="alterar(cidade._id)">Editar</a> <a href="#" @click="deletar(cidade._id)">Deletar</a></td>    
        </tr>
        </tbody>

    </table>
        
  </div>
</template>

<script>
const axios = require('axios');

export default {
    name: 'Cidade',
    props: {
        msg: String
    },
    data () {
        return {
            showModal: false,
            estados: null,
            cidades: null,
            form:{
                id: null,
                nome : null,
                estadoId: null
            },
        }
    },
    methods:{
        async listar(){
            this.estados = await axios.get('http://localhost/rest/estado/listar/').then(response => (this.estados = response.data.Estado));
            this.cidades = await axios.get('http://localhost/rest/cidade/listar/').then(response => (this.cidades = response.data.Cidade));
        },
       
        filtrar(id){
            if (id){
                var estado = this.estados.filter(estado => estado._id === id)
                return estado[0].nome;
            }
        },
        
        cadastrar(){
            axios.post('http://localhost/rest/cidade/cadastrar', {
                nome: this.form.nome,
                estadoId: this.form.estadoId
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
            axios.put('http://localhost/rest/cidade/alterar/'+id, {
                nome: this.form.nome,
                estadoId: this.form.estadoId
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
            axios.delete('http://localhost/rest/cidade/deletar/'+id);
            //.then(response => (console.log(response)));
            alert('Deletado com Sucesso');
            this.listar();
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
