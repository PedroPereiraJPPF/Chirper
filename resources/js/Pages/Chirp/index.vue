<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import InputError from '@/Components/InputError.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import {useForm, Head} from '@inertiajs/vue3'
    import Chirp from '@/components/Chirp.vue'

    const form = useForm({
        message: '',
    })

    defineProps(['chirps', 'userId'])

</script>

<template>
    <Head title="chirps" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('chirps.store'), { onSuccess: () => form.reset() })">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <PrimaryButton class="mt-4" @click="submitChangeState" :disabled="submit">Chirp</PrimaryButton>
            </form>
        </div>
        <div class="max-w-2xl bg-white shadow-sm rounded-lg divide-y mx-auto px-4">
            <Chirp 
                v-for="(chirp, index) in chirps"
                :key="chirp.id"
                :chirp="chirp"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script>
    export default {
        name: "app",
        data() {
            return {
                nome: "nome",
                submit: false,
                teste: []
            }
        },
        created() {
            Echo.channel(`testeBroadcast`)
                .listen('Teste', (e) => {
                    if(e.chirp.user_id != this.userId){
                        this.chirps.unshift(e.chirp)
                        new Audio('music/message-13716.mp3').play()
                    }
                })
                .listen('deleteChirp', (e) => {
                    let indiceParaRemover = this.chirps.findIndex(chirp => chirp.id === e.id);
                    if (indiceParaRemover !== -1) {
                        this.chirps.splice(indiceParaRemover, 1);
                    }
                })
        }
    }
</script>