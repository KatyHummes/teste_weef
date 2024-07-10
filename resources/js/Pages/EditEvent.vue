<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const toast = useToast();
const props = defineProps({
    event: Object,
});

const form = useForm('put', route('event.update', props.event.id), {
    name: props.event.name,
    date: props.event.date,
    responsible: props.event.responsible,
    state: props.event.state,
    city: props.event.city,
    neighborhood: props.event.neighborhood,
    street: props.event.street,
    number: props.event.number,
    complement: props.event.complement,
    phone: props.event.phone,
    image: props.event.image,
});

const submit = () => {

    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success("Evento atualizado com Sucesso!", {
                position: 'top-right',
            });
        },
        onError: () => {
            toast.error("Erro ao atualizar Evento!", {
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

function getImageUrl(path) {
    return `/${path}`;
}


</script>

<template>
    <AppLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <v-container class="m-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold mb-4">{{ event.name }}</h2>
                    </div>
                    <v-carousel hide-delimiter-background>
                        <v-carousel-item v-for="image in event.images" :key="image.id">
                            <v-img :src="getImageUrl(image.photo_path)" alt="Imagem do imóvel" class="carousel-image" />
                        </v-carousel-item>
                    </v-carousel>
                </v-container>

                <form @submit.prevent="submit">
                    <v-card-text>
                        <h2>Dados Pessoais:</h2>
                        <v-container>
                            <v-text-field label="Nome do Evento:*" v-model="form.name" variant="outlined"
                                @change="form.validate('name')"></v-text-field>
                            <span v-if="form.invalid('name')" class="text-base text-red-500">
                                {{ form.errors.name }}
                            </span>
                        </v-container>
                        <v-container>
                            <v-text-field label="Nome do Responsável:*" v-model="form.responsible" variant="outlined"
                                @change="form.validate('responsible')"></v-text-field>
                            <span v-if="form.invalid('responsible')" class="text-base text-red-500">
                                {{ form.errors.responsible }}
                            </span>
                        </v-container>
                        <v-container>
                            <v-text-field label="Telefone:*" v-model="form.phone" variant="outlined"
                                v-mask="'(##) #####-####'" @change="form.validate('phone')"></v-text-field>
                            <span v-if="form.invalid('phone')" class="text-base text-red-500">
                                {{ form.errors.phone }}
                            </span>
                        </v-container>
                        <v-container>
                            <v-text-field label="Selecione a data:*" v-model="form.date" variant="outlined"
                                type="datetime-local" @change="form.validate('date')"></v-text-field>
                            <span class="text-base text-red-500">
                                {{ form.errors.date }}
                            </span>
                        </v-container>
                        <v-container>
                            <v-file-input label="Fotos:*" v-model="form.images" variant="outlined"
                                @change="onImageChange" chips multiple></v-file-input>
                            <div v-if="form.invalid('images')" class="font-semibold text-red-500">
                                {{ form.errors.images }}
                            </div>
                            <!-- Container para pré-visualizações de fotos com Tailwind CSS -->
                            <v-container class="flex flex-wrap -m-1">
                                <div v-for="(previewUrl, index) in form.imagePreviews" :key="index" class="p-1">
                                    <img :src="previewUrl" class="h-24 w-24 object-cover rounded-lg" />
                                </div>
                            </v-container>
                        </v-container>

                        <h2>Endereço:</h2>
                        <v-container class="grid gap-4 mb-7">
                            <v-container>
                                <v-text-field label="Estado" v-model="form.state" variant="outlined"></v-text-field>
                            </v-container>
                            <v-container>
                                <v-text-field label="Cidade" v-model="form.city" variant="outlined"></v-text-field>
                            </v-container>
                            <v-container>
                                <v-text-field label="Bairro" v-model="form.neighborhood"
                                    variant="outlined"></v-text-field>
                            </v-container>
                            <v-container>
                                <v-text-field label="Rua" v-model="form.street" variant="outlined"></v-text-field>
                            </v-container>
                            <v-container>
                                <v-text-field label="Número" v-model="form.number" variant="outlined"></v-text-field>
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
            </div>
        </div>
    </AppLayout>
</template>