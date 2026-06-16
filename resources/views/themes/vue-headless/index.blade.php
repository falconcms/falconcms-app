<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue Headless Theme - Falcon CMS</title>
    <!-- CDN for Vue 3 and Tailwind -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [v-cloak] { display: none; }
        .fade-enter-active, .fade-leave-active { transition: opacity 0.5s ease; }
        .fade-enter-from, .fade-leave-to { opacity: 0; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div id="app" v-cloak>
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
                <div @click="goToHome" class="text-2xl font-black text-blue-600 cursor-pointer flex items-center gap-2">
                    <span class="material-icons">bolt</span>
                    @{{ settings.site_title || 'Vue Theme' }}
                </div>
                <div class="flex gap-6 font-medium text-gray-600">
                    <button @click="goToHome" class="hover:text-blue-600">Home</button>
                    <a href="/admin" class="bg-gray-100 px-4 py-1.5 rounded-full text-sm hover:bg-gray-200">Admin</a>
                </div>
            </div>
        </nav>

        <!-- Header -->
        <header v-if="view === 'home'" class="bg-blue-600 py-16 text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Welcome to Headless Vue Theme</h1>
            <p class="text-blue-100 text-lg">Powered by Falcon CMS REST API v3.1.4</p>
        </header>

        <!-- Main Content -->
        <main class="max-w-5xl mx-auto px-4 py-12">
            
            <!-- Posts Grid (Home View) -->
            <div v-if="view === 'home'">
                <div v-if="loading" class="flex justify-center py-20">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="post in posts" :key="post.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <img :src="post.featured_image || 'https://via.placeholder.com/600x400'" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span v-for="cat in post.categories" class="text-[10px] font-bold uppercase tracking-wider text-blue-500 bg-blue-50 px-2 py-0.5 rounded">
                                    @{{ cat.name }}
                                </span>
                            </div>
                            <h2 @click="goToSingle(post.slug)" class="text-xl font-bold mb-3 hover:text-blue-600 cursor-pointer leading-tight">
                                @{{ post.title }}
                            </h2>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-3">
                                @{{ post.excerpt }}
                            </p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                <span class="text-xs text-gray-400">@{{ post.published_at }}</span>
                                <button @click="goToSingle(post.slug)" class="text-blue-600 font-bold text-sm flex items-center">
                                    Read More <span class="material-icons text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Post View -->
            <div v-if="view === 'single'" class="max-w-3xl mx-auto">
                <button @click="goToHome" class="mb-8 flex items-center gap-2 text-gray-500 hover:text-blue-600 font-medium">
                    <span class="material-icons text-sm">arrow_back</span> Back to Home
                </button>

                <div v-if="loading" class="flex justify-center py-20">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>

                <article v-else-if="currentPost" class="prose prose-lg max-w-none">
                    <img :src="currentPost.featured_image || 'https://via.placeholder.com/1200x600'" class="w-full h-auto rounded-3xl mb-8 shadow-lg">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">@{{ currentPost.categories[0]?.name }}</span>
                        <span class="text-gray-400 text-sm">@{{ currentPost.published_at }}</span>
                    </div>
                    <h1 class="text-4xl font-black text-gray-900 mb-8 leading-tight">@{{ currentPost.title }}</h1>
                    <div class="text-gray-700 leading-relaxed text-lg space-y-6" v-html="currentPost.content"></div>
                </article>
            </div>

        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-12 mt-20">
            <div class="max-w-5xl mx-auto px-4 text-center">
                <p class="text-gray-400 text-sm mb-2">&copy; 2026 Falcon CMS Vue Headless Theme.</p>
                <p class="text-gray-300 text-[10px] uppercase tracking-widest font-bold">Built with Freedom & Speed</p>
            </div>
        </footer>
    </div>

    <script>
        const { createApp, ref, onMounted } = Vue;

        createApp({
            setup() {
                const view = ref('home'); // home or single
                const posts = ref([]);
                const settings = ref({});
                const currentPost = ref(null);
                const loading = ref(false);

                const fetchPosts = async () => {
                    loading.value = true;
                    try {
                        const res = await fetch('/api/v1/posts');
                        const json = await res.json();
                        posts.value = json.data;
                    } catch (e) {
                        console.error("API Error:", e);
                    }
                    loading.value = false;
                };

                const fetchSettings = async () => {
                    try {
                        const res = await fetch('/api/v1/settings');
                        const json = await res.json();
                        settings.value = json.data;
                    } catch (e) {}
                };

                const goToHome = () => {
                    view.value = 'home';
                    currentPost.value = null;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                };

                const goToSingle = async (slug) => {
                    loading.value = true;
                    view.value = 'single';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    try {
                        const res = await fetch(`/api/v1/posts/${slug}`);
                        const json = await res.json();
                        currentPost.value = json.data;
                    } catch (e) {
                        console.error("Single Post Error:", e);
                    }
                    loading.value = false;
                };

                onMounted(() => {
                    fetchPosts();
                    fetchSettings();
                });

                return {
                    view, posts, settings, currentPost, loading,
                    goToHome, goToSingle
                }
            }
        }).mount('#app')
    </script>
</body>
</html>
