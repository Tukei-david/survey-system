import { createStore } from "vuex"
import axiosClient from "../axios"

const tmpSurveys = [
    {
        id: 100,
        title: "David Tukei YouTube channel content",
        slug: "David Tukei YouTube channel content",
        status: "draft",
        image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        description: "My name is David, <br> I am Web Developer with 4+ years of experience, free eduactional content",
        created_at: "2023-01-02 10:55:00",
        updated_at: "2023-01-02 10:55:00",
        expire_date: "2023-01-10 10:55:00",
        questions: [
            {
                id: 1,
                type: "select",
                question: "From which country are you?",
                description: null,
                data: {
                    options: [
                        { uuid: "1415ff41-91f2-4223-a868-144f069fc741", text: "USA" },
                        { uuid: "e0c8fd50-366c-42ee-97f8-0db7bd2b7df8", text: "Kenya" },
                        { uuid: "83b0f53b-f80d-4bab-99f0-adbcd944777c", text: "Uganda" },
                        { uuid: "9f1ed699-e83a-41d9-b925-abf1902fa4e3", text: "Tanzania" },
                    ]
                }
            },
            {
                id: 2,
                type: "checkbox",
                question: "Which languagee videos do you want to see on my channel?",
                description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque. Sequi suscipit alias, repellendus ipsam maxime impedit asperiores cumque! Libero, nemo? Sit.",
                data: {
                    options: [
                        { uuid: "35cf213b-6f81-4316-a2ba-0ffbae5d6b06", text: "Javascript" },
                        { uuid: "4fa6034d-a7d0-49fc-a7aa-ec1281c0708b", text: "HTML + CSS" },
                        { uuid: "947a8c9e-16ad-4c20-a133-2b9df633286c", text: "All the above" },
                        { uuid: "e5724773-6833-4bec-8d32-89123733b021", text: "Python" },
                    ]
                }
            },
            {
                id: 3,
                type: "checkbox",
                question: "Which PHP frameworks videos do you want to see on my channel?",
                description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque. Sequi suscipit alias, repellendus ipsam maxime impedit asperiores cumque! Libero, nemo? Sit.",
                data: {
                    options: [
                        { uuid: "71fb59df-7a89-4776-9f14-fe3f5dec46f0", text: "Laravel" },
                        { uuid: "3697570e-b2a5-4ebc-ad37-eff47fe7a60b", text: "Yii2" },
                        { uuid: "c6b53089-2c0d-4484-9654-b545e8a81e6d", text: "Codeigniter" },
                        { uuid: "b83cf189-be9b-4df9-9d25-2fbceab0876d", text: "Symfony" },
                    ]
                }

            },
            {
                id: 4,
                type: "radio",
                question: "Which Laravel frameworks do you love most?",
                description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque. Sequi suscipit alias, repellendus ipsam maxime impedit asperiores cumque! Libero, nemo? Sit.",
                data: {
                    options: [
                        { uuid: "97272ceb-b263-4fd5-97ab-0049f9c1fe4f", text: "Laravel 5" },
                        { uuid: "dffb4c94-6f3c-4cee-8975-d58c22e49959", text: "Laravel 6" },
                        { uuid: "789273be-b879-44bb-aa99-fe242950e1be", text: "Laravel 7" },
                        { uuid: "1a7eec48-ea00-4e8f-86a8-f84d9885edaa", text: "Laravel 8" },
                    ]
                }

            },
            {
                id: 5,
                type: "radio",
                question: "WHich type of projects do you want to see on my channel built with Laravel?",
                description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque. Sequi suscipit alias, repellendus ipsam maxime impedit asperiores cumque! Libero, nemo? Sit.",
                data: {
                    options: [
                        { uuid: "97272ceb-b263-4fd5-97ab-0049f9c1fe4f", text: "REST API" },
                        { uuid: "dffb4c94-6f3c-4cee-8975-d58c22e49959", text: "E-commerce" },
                        { uuid: "789273be-b879-44bb-aa99-fe242950e1be", text: "Real Estate" },
                        { uuid: "1a7eec48-ea00-4e8f-86a8-f84d9885edaa", text: "All the above" },
                    ]
                }

            },
            {
                id: 6,
                type: "text",
                question: "What's your favourite youtube channel?",
                description: null,
                data: {},
            },
            {
                id: 7,
                type: "textarea",
                question: "What do you think about TheDavidic Channel?",
                description: "Write your honest opnion. Everything is anonymous.",
                data: {},
            },
        ],
    },
    {
        id: 200,
        title: "Tailwind 3",
        slug: "tailwind-3",
        status: "active",
        image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. <code>Expedita</code> inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque.",
        created_at: "2023-01-02 11:00:00",
        updated_at: "2023-01-02 11:00:00",
        expire_date: "2023-01-10 00:00:00",
        questions: [],
    },
    {
        id: 300,
        title: "Vue 3",
        slug: "vue-3",
        status: "active",
        image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. <code>Expedita</code> inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque.",
        created_at: "2023-01-02 17:00:00",
        updated_at: "2023-01-02 17:00:00",
        expire_date: "2023-01-10 00:00:00",
        questions: [],
    },
    {
        id: 400,
        title: "Laravel 3",
        slug: "laravel-3",
        status: "active",
        image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. <code>Expedita</code> inventore asperiores nam repellendus doloremque quia necessitatibus, magni eaque.",
        created_at: "2023-01-02 18:00:00",
        updated_at: "2023-01-02 18:00:00",
        expire_date: "2023-01-10 18:00:00",
        questions: [],
    },
]

const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem('TOKEN'),
        },
        surveys: [...tmpSurveys],
    },
    getters: {},
    actions: {
        register ({ commit }, user) {
            return axiosClient.post('/register', user)
                .then(({data}) => {
                    commit('setUser', data)
                    return data
                })
        },
        login ({ commit }, user) {
            return axiosClient.post('/login', user)
                .then(({data}) => {
                    commit('setUser', data)
                    return data
                })
        },
        logout({ commit }) {
            return axiosClient.post('/logout')
                .then(response => {
                    commit('logout')
                    return response
                })
        }
    },
    mutations: {
        logout: (state) => {
            state.user.data = {}
            state.user.token = null
        },
        setUser: (state, userData) => {
            state.user.token = userData.token
            state.user.data = userData.user
            sessionStorage.setItem('TOKEN', userData.token)
        }
    },
    modules: {}
})

export default store