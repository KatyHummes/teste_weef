<script setup>
import { ref, computed, defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const toast = useToast();
const props = defineProps({
    events: Array,
});

// const dateReset = new Date(props.events.date);
const form = useForm('post', route('events.store'), {
    name: '',
    date: '',
    responsible: '',
    city: '',
    neighborhood: '',
    street: '',
    number: '',
    complement: '',
    phone: '',
    images: [],
});

const submit = () => {
    
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success("Evento criado com Sucesso!", {
                position: 'top-right',
            });
        },
        onError: () => {
            toast.error("Erro ao criar Evento!", {
                position: 'top-right',
            });
        }
    });
};

const onImageChange = (event) => {
    form.images = [];
    form.imagePreviews = [];

    for (let i = 0; i < event.target.files.length; i++) {
        const file = event.target.files[i];
        form.images.push(file);
        const previewUrl = URL.createObjectURL(file);
        form.imagePreviews.push(previewUrl);
    }
};

const updateDateReset = (newValue) => {
    if (!newValue) {
        form.date = null;
    } else {
        const newDate = new Date(newValue);
        if (!isNaN(newDate.getTime())) {
            form.date = newDate;
        }
    }
    validateDate();
}

// configuração de datas
const isMenuOpen = ref(false);
const formattedDate = computed(() => {
    if (!form.date) return '';
    const dateObj = new Date(form.date);
    return dateObj.toLocaleDateString('pt-BR');
});

// Filtros e paginação
const search = ref('');
const page = ref(1);
const itemsPerPage = 10;

const filteredevents = computed(() => {
    const searchTerm = search.value.toLowerCase().trim();
    page.value = 1
    return props.events.filter(event => {
        return (
            event.name.toLowerCase().includes(searchTerm) ||
            event.date.toLowerCase().includes(searchTerm) ||
            event.responsible.toString().toLowerCase().includes(searchTerm) ||
            event.phone.toLowerCase().includes(searchTerm)
        );
    });
});

const displayedEvents = computed(() => {
    const start = (page.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredevents.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredevents.value.length / itemsPerPage);
});

const updatePage = (newPage) => {
    page.value = newPage;
};

// Função para formatar a data da tabela
const formatDate = (dateString) => {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const formatPhone = (phone) => {
    // Formatação do telefone no formato (##) #####-####
    const phonePattern = /(\d{2})(\d{5})(\d{4})/;
    return phone.replace(phonePattern, '($1) $2-$3');
};

// Modal deletar Eventos
const showDelete = ref(false);
const formDelete = ref();

const openDeleteModal = (id) => {
    console.log(id);
    showDelete.value = true;
    formDelete.value = useForm('delete', `/delete-evento/${id}`, {
        id: id
    });
}

const closeDeleteModal = () => {
    showDelete.value = false;
}

const deleteEvent = () => {
    formDelete.value.submit({
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            toast.success("Evento excluído com Sucesso!", {
                position: 'top-right',
            });
        }
    });
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gerenciar Eventos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-col cols="auto" class="flex justify-center mb-5">
                        <v-dialog max-width="800">
                            <template v-slot:activator="{ props: activatorProps }">
                                <v-btn v-bind="activatorProps" color="primary" text="Incluir Eventos"
                                    variant="flat"></v-btn>
                            </template>

                            <template v-slot:default="{ isActive }">
                                <v-card title="Incluir Eventos">
                                    <form @submit.prevent="submit">
                                        <v-card-text>
                                            <h2>Dados Pessoais:</h2>
                                            <v-container>
                                                <v-text-field label="Nome do Evento:*" v-model="form.name"
                                                    variant="outlined" @change="form.validate('name')"></v-text-field>
                                                <span v-if="form.invalid('name')" class="text-base text-red-500">
                                                    {{ form.errors.name }}
                                                </span>
                                            </v-container>
                                            <v-container>
                                                <v-text-field label="Nome do Responsável:*" v-model="form.responsible"
                                                    variant="outlined"
                                                    @change="form.validate('responsible')"></v-text-field>
                                                <span v-if="form.invalid('responsible')" class="text-base text-red-500">
                                                    {{ form.errors.responsible }}
                                                </span>
                                            </v-container>
                                            <v-container>
                                                <v-text-field label="Telefone:*" v-model="form.phone" variant="outlined"
                                                    v-mask="'(##) #####-####'"
                                                    @change="form.validate('phone')"></v-text-field>
                                                <span v-if="form.invalid('phone')" class="text-base text-red-500">
                                                    {{ form.errors.phone }}
                                                </span>
                                            </v-container>
                                            <v-container>
                                                <v-text-field label="Selecione a data:*" v-model="form.date"
                                                    v-bind="props" variant="outlined" type="datetime-local"
                                                    @change="form.validate('date')"></v-text-field>
                                                <span class="text-base text-red-500">
                                                    {{ form.errors.date }}
                                                </span>
                                            </v-container>
                                            <v-container>
                                                <v-file-input label="Fotos:*" v-model="form.images" variant="outlined"
                                                    @change="onImageChange" chips multiple></v-file-input>
                                                <div v-if="form.invalid('photos')" class="font-semibold text-red-500">
                                                    {{ form.errors.photos }}
                                                </div>
                                                <!-- Container para pré-visualizações de fotos com Tailwind CSS -->
                                                <v-container class="flex flex-wrap -m-1">
                                                    <div v-for="(previewUrl, index) in form.imagePreviews" :key="index"
                                                        class="p-1">
                                                        <img :src="previewUrl"
                                                            class="h-24 w-24 object-cover rounded-lg" />
                                                    </div>
                                                </v-container>
                                            </v-container>

                                            <h2>Endereço:</h2>
                                            <v-container class="grid gap-4 mb-7">
                                                <v-container>
                                                    <v-text-field label="Estado" v-model="form.state"
                                                        variant="outlined"></v-text-field>  
                                                </v-container>
                                                <v-container>
                                                    <v-text-field label="Cidade" v-model="form.city"
                                                        variant="outlined"></v-text-field>
                                                </v-container>
                                                <v-container>
                                                    <v-text-field label="Bairro" v-model="form.neighborhood"
                                                        variant="outlined"></v-text-field>
                                                </v-container>
                                                <v-container class="flex gap-4">
                                                    <v-text-field label="Rua" v-model="form.street"
                                                        variant="outlined"></v-text-field>
                                                    <v-text-field label="Número" v-model="form.number"
                                                        variant="outlined"></v-text-field>
                                                </v-container>
                                                <v-container>
                                                    <v-textarea label="Complemento" v-model="form.complement"
                                                        variant="outlined"></v-textarea>
                                                </v-container>
                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>

                                            <v-btn text="Cancelar" @click="isActive.value = false"></v-btn>
                                            <v-btn type="submit">Salvar</v-btn>
                                        </v-card-actions>
                                    </form>
                                </v-card>
                            </template>
                        </v-dialog>
                    </v-col>

                    <v-card title="Pessoas" flat>
                        <template v-slot:text>
                            <v-text-field v-model="search" label="Search" prepend-inner-icon="mdi-magnify"
                                variant="outlined" hide-details single-line></v-text-field>
                        </template>
                    </v-card>

                    <v-table>
                        <thead>
                            <tr>
                                <th class="text-left">Id</th>
                                <th class="text-left">Nome</th>
                                <th class="text-left">Data do Evento</th>
                                <th class="text-left">Responsável</th>
                                <th class="text-left">Telefone</th>
                                <th class="text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="event in displayedEvents" :key="event.id">
                                <td>{{ event.id }}</td>
                                <td>{{ event.name }}</td>
                                <td>{{ formatDate(event.date) }}</td>
                                <td>{{ event.responsible }}</td>
                                <td>{{ formatPhone(event.phone) }}</td>
                                <td>
                                    <div class="flex gap-4">
                                        <Link :href="route('event.edit', event.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        </Link>

                                        <button size="small" @click="openDeleteModal(event.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                    <div class="text-center pt-2">
                        <v-pagination v-model="page" :length="pageCount" @input="updatePage"></v-pagination>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Exclusão de Eventos -->
    <Modal :show="showDelete" @close="closeDeleteModal">
        <div class="p-4">
            <form @submit.prevent="deleteEvent()">
                <h2 class="flex items-center justify-center border-b-4 text-xl p-4 m-4 font-bold">
                    Tem certeza que deseja excluir este Evento?
                </h2>
                <div class="flex justify-between">
                    <v-btn type="button" @click="closeDeleteModal">
                        Cancelar
                    </v-btn>
                    <v-btn type="submit">
                        Excluir
                    </v-btn>
                </div>
            </form>
        </div>
    </Modal>
</template>
