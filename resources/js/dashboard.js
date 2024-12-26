document.addEventListener("DOMContentLoaded", () => {
    const activityList = document.getElementById("activity-list");
    const paginationControls = document.getElementById("pagination-controls");

    
    const fetchActivities = (page = 1) => {
        fetch(`/activities?page=${page}`) 
            .then(response => response.json())
            .then(data => {
                renderActivities(data.data); 
                renderPagination(data); 
            })
            .catch(error => console.error("Erro ao carregar atividades:", error));
    };


    const renderActivities = (activities) => {
        activityList.innerHTML = ""; 
        activities.forEach(activity => {
            const listItem = document.createElement("li");
            listItem.className = "list-group-item";
            listItem.innerHTML = `
                <strong>${new Date(activity.created_at).toLocaleDateString()} ${new Date(activity.created_at).toLocaleTimeString()}</strong> - 
                ${activity.description} 
                ${activity.user ? `<span class="text-muted">por ${activity.user.name}</span>` : ""}
            `;
            activityList.appendChild(listItem);
        });
    };

  
    const renderPagination = (data) => {
        paginationControls.innerHTML = ""; 

        const { current_page, last_page } = data;

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

    
    fetchActivities();
});