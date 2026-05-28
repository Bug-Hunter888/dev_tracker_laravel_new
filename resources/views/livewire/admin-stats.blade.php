<div wire:poll.10s class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-px bg-gray-800">

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">TOTAL_USERS</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-neon-green">{{ $adminUsers }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">ADMINS</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $bannedUsers }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">DISABLED</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-blue-400">{{ $totalTeams }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">TEAMS</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-yellow-500">{{ $totalProjects }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">PROJECTS</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-purple-400">{{ $totalSprints }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">SPRINTS</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-white">{{ $totalTasks }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">TOTAL_TASKS</p>
    </div>

    <div class="bg-black p-5 text-center">
        <p class="text-3xl font-bold text-neon-green">{{ $doneTasks }}</p>
        <p class="text-xs text-gray-600 mt-1 tracking-widest">SHIPPED</p>
    </div>

</div>
