<template>
    <app-layout title="Libreria">
        <!-- MODAL PARA RESERVAR EL LIBRO -->
        <div v-show="formReserve" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="flex flex-col w-1/2 p-5 bg-white rounded-lg gap-10">
                <h1 class="text-xl font-bold">Reserva de Libro</h1>
                <div class="flex">

                    <div class="flex flex-col items-start flex-1 gap-2">
                        <p><span class="font-bold">Nombre del libro: </span>{{ bookReserve.name }}</p>
                        <p><span class="font-bold">Autor: </span>{{ bookReserve.author }}</p>
                        <p><span class="font-bold">Descripción: </span>{{ bookReserve.description }}</p>
                        <div class="flex items-center gap-5">
                            <p><span class="font-bold">Dias: </span></p>
                            <select v-model="bookReserve.days">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-center flex-1 ">
                        <div class="w-48 h-48 bg-gray-100 rounded-lg">

                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-10">
                    <button @click="my_reserve()">Confirmar</button>
                    <button @click="formReserve = false">Cancelar</button>
                </div>
            </div>
        </div>
        <!-- PANEL DE VISTA DE LIBROS Y RESERVAS -->
        <div class="w-3/4 p-5 mx-auto mt-5 bg-white rounded-lg shadow-md">
            <div class="flex justify-between w-full items-centerr">
                <div class="flex flex-col gap-5">
                    <h6 class="text-xl"><span class="font-bold">Mi Nombre:</span> {{$page.props.user.name}}</h6>
                    <h6><span class="font-bold">Mi Email:</span> {{$page.props.user.email}}</h6>
                    <h6><span class="font-bold">Mis Reservas:</span> {{ total_reserves }}</h6>
                </div>
                <div class="overflow-hidden rounded-full">
                    <img v-bind:src="$page.props.user.profile_photo_url" alt="" class="w-32">
                </div>
            </div>
            <!-- MENU PARA CAMBIAN DE SECCION -->
            <div class="flex">
                <div class="flex items-center justify-center flex-1 p-5 rounded-lg cursor-pointer" :class="section[0] ? 'bg-gray-200' : ''" @click="change_section(1)">
                    <p class="text-xl font-bold text-gray-600">Mis Reservas</p>
                </div>
                <div class="flex items-center justify-center flex-1 p-5 rounded-lg cursor-pointer" :class="section[1] ? 'bg-gray-200' : ''" @click="change_section(2)">
                    <p class="text-xl font-bold text-gray-600">Libros</p>
                </div>
            </div>
            <div class="flex flex-col">
                <!-- SECCION PERSONAL LISTADO DE RESERVAS -->
                <div v-show="section[0]">
                    <div v-if="reserves.length > 0">
                        <div class="flex p-3 font-bold bg-white even:bg-gray-200">
                            <div class="w-1/2">
                                <p>Nombre</p>
                            </div>
                            <div class="w-1/4">
                                <p>Autor</p>
                            </div>
                            <div class="w-1/4">
                            </div>
                        </div>

                        <div v-for="reserve in reserves" class="flex p-3 bg-white even:bg-gray-200">
                            <div class="w-1/2">
                                {{ reserve.book.name }}
                            </div>
                            <div class="w-1/4">
                                {{ reserve.book.author }}
                            </div>
                            <div class="w-1/4">
                                <button class="font-bold text-red-600" @click.defer="deleteReserve(reserve.id)">Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <p v-else class="flex items-center justify-center p-10 text-2xl font-bold text-gray-400">No hay reservaciones</p>
                </div>
                <!-- SECCION PARA BUSCAR LIBROS DISPONIBLES -->
                <div v-show="section[1]">
                    <div class="flex items-center p-3 gap-10">
                        <p>Selecciona la categoria</p>
                        <select v-model="category" @change="all_books()">
                            <option value="all">Todos</option>
                            <option v-for="cat in all_cat" :key="'categoria' + cat.id" :value="cat.name" >{{cat.name}}</option>
                        </select>
                    </div>
                    <div v-if="books.length > 0">
                        <div class="flex p-3 font-bold bg-white even:bg-gray-200">
                            <div class="w-1/2">
                                <p>Nombre</p>
                            </div>
                            <div class="w-1/4">
                                <p>Autor</p>
                            </div>
                            <div class="w-1/4">
                            </div>
                        </div>
                        <div v-for="book in books" class="flex p-2 bg-white even:bg-gray-200">
                            <div class="w-1/2">
                                {{ book.name }}
                            </div>
                            <div class="w-1/4">
                                {{ book.author }}
                            </div>
                            <div class="w-1/4">
                                <button class="font-bold text-green-600" @click.defer="reserve(book)">Reservar</button>
                            </div>
                        </div>
                    </div>
                    <p v-else class="flex items-center justify-center p-10 text-2xl font-bold text-gray-400">No hay libros en esta categoria</p>
                </div>

            </div>
        </div>
    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    components: {
        AppLayout,
    },
    props: {
        count_reserves: Number,
        all_cat : Array,
    },
    data () {
        return {
            section : [true, false],
            reserves : [],
            books : [],
            category : 'all',
            formReserve: false,
            bookReserve: {
                id: null,
                name: null,
                author: null,
                description: null,
                days: 1
            },
            total_reserves : this.count_reserves
        }
    },
    mounted(){
        this.change_section(1)
    },
    methods: {
        change_section(section){
            // this method is for change the section 
            switch (section) {
                case 1:
                    this.section = [true,false]
                    this.all_reserves()
                    break;
                case 2:
                    this.section = [false,true]
                    this.all_books()
                    break;
            }
        },
        all_reserves()
        {
            axios.post(this.route('reserve.all'))
                .then(res => {
                    this.reserves = res.data
                    this.total_reserves = this.reserves.length
                })
        },
        my_reserve(){
            this.formReserve = false
            axios.post(this.route('reserve.book', [this.bookReserve.id, this.bookReserve.days]))
                .then(res => {
                    if(res.data)
                    {
                        this.change_section(1)
                    }
                })
        },
        all_books(){
            axios.post(this.route('category.books', [this.category]))
                .then(res => {
                    this.books = res.data
                })
        },
        reserve(book)
        {
            this.bookReserve.id = book.id
            this.bookReserve.name = book.name
            this.bookReserve.author = book.author
            this.formReserve = true
        },
        deleteReserve(book)
        {
            axios.post(this.route('reserve.delete', [book]))
                .then(res => {
                    if(res.data)
                    {
                        this.all_reserves()
                    }
                })
        }
    },
}
</script>
