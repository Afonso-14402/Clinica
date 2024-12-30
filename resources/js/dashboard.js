document.addEventListener("DOMContentLoaded", () => {
    // Captura os elementos do DOM
    const activityList = document.getElementById("activity-list");
    const paginationControls = document.getElementById("pagination-controls");

    // Verifica se esta página contém os elementos necessários
    if (!activityList || !paginationControls) {
        // Se não encontrar, interrompe a execução deste script
        return;
    }

    // Função para buscar atividades
    const fetchActivities = (page = 1) => {
        fetch(`/activities?page=${page}`) // Substitua pela rota correta no backend
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na resposta do servidor: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                renderActivities(data.data); // Renderizar a lista de atividades
                renderPagination(data); // Renderizar a paginação
            })
            .catch(error => console.error("Erro ao carregar atividades:", error));
    };

   // Função para renderizar as atividades
const renderActivities = (activities) => {
    // Limpa a lista antes de renderizar novos itens
    activityList.innerHTML = "";

    activities.forEach(activity => {
        const listItem = document.createElement("li");
        listItem.className = "list-group-item";
        listItem.dataset.id = activity.id; // Adiciona o ID da atividade para controle
        listItem.innerHTML = `
            <strong>${new Date(activity.created_at).toLocaleDateString()} ${new Date(activity.created_at).toLocaleTimeString()}</strong> - 
            ${activity.description} 
            ${activity.user ? `<span class="text-muted">por ${activity.user.name}</span>` : ""}
        `;
        activityList.appendChild(listItem);
    });
};

    // Função para renderizar os botões de paginação
    const renderPagination = (data) => {
        const { current_page, last_page } = data;

        // Atualiza ou cria botões de paginação
        paginationControls.innerHTML = "";

        // Botão "Anterior"
        if (current_page > 1) {
            const prevButton = document.createElement("button");
            prevButton.className = "btn btn-primary btn-sm mx-1";
            prevButton.innerText = "Anterior";
            prevButton.addEventListener("click", () => fetchActivities(current_page - 1));
            paginationControls.appendChild(prevButton);
        }

        // Números das páginas
        for (let i = 1; i <= last_page; i++) {
            const pageButton = document.createElement("button");
            pageButton.className = "btn btn-outline-primary btn-sm mx-1";
            pageButton.innerText = i;
            if (i === current_page) pageButton.classList.add("active");
            pageButton.addEventListener("click", () => fetchActivities(i));
            paginationControls.appendChild(pageButton);
        }

        // Botão "Próximo"
        if (current_page < last_page) {
            const nextButton = document.createElement("button");
            nextButton.className = "btn btn-primary btn-sm mx-1";
            nextButton.innerText = "Próximo";
            nextButton.addEventListener("click", () => fetchActivities(current_page + 1));
            paginationControls.appendChild(nextButton);
        }
    };

    // Carrega as atividades iniciais na primeira página
    fetchActivities();
});
