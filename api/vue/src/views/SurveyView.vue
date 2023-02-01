<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="font-bold text-3xl text-gray-900">
                    <!-- If model has id --- dispaly the survey title else show create survey -->
                    {{  model.id ? model.title : 'Create a survey' }}
                </h1>
            </div>
        </template>
        <form @submit.prevent="saveSurvey">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <div class="mt-1 flex items-center">
                            <img 
                                v-if="model.image"
                                :src="model.image" 
                                :alt="model.image"
                                class="w-64 h-48 object-cover"
                            >
                            <span
                                v-else
                                clas
                            >

                            </span>
                        </div>
                  
                
                    </div>

                </div>
            </div>
        </form>
    </PageComponent>
</template>

<script setup>
import store from '../store';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import PageComponent from '../components/PageComponent.vue';

const route = useRoute()

let model = ref({
    title: "",
    status: false,
    description: null,
    image: null,
    expire_date: null,
    questions: [],
})

if (route.params.id) {
    model.value = store.state.surveys.find(
        (s) => s.id === parseInt(route.params.id) 
    )
}


</script>

<style></style>