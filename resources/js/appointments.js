document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('eventForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Evento salvo com sucesso!');
            document.getElementById('addEventModal').classList.remove('show');
        });
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const doctorSearch = document.getElementById('doctor-search');
    if (doctorSearch) {
        doctorSearch.addEventListener('input', function () {
            const query = this.value;

            if (query.length > 0) {
                fetch(`/autocomplete/doctors?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        const list = document.getElementById('doctor-list');
                        list.innerHTML = ''; // Limpa a lista

                        if (data.length > 0) {
                            list.style.display = 'block';

                            data.forEach(doctor => {
                                const item = document.createElement('li');
                                item.textContent = doctor.name;
                                item.className = 'list-group-item';
                                item.style.cursor = 'pointer';

                                // Seleciona o médico clicado
                                item.addEventListener('click', function () {
                                    doctorSearch.value = doctor.name;
                                    document.getElementById('doctor_user_id').value = doctor.id; // Atribui o ID ao campo oculto
                                    list.style.display = 'none'; // Oculta a lista

                                    // Buscar especialidades para o médico selecionado
                                    fetch(`/doctor/${doctor.id}/specialties`)
                                        .then(response => response.json())
                                        .then(specialties => {
                                            const specialtiesSelect = document.querySelector('[name="specialties_id"]');
                                            if (specialtiesSelect) {
                                                specialtiesSelect.innerHTML = ''; // Limpa as especialidades anteriores

                                                specialties.forEach(specialty => {
                                                    const option = document.createElement('option');
                                                    option.value = specialty.id;
                                                    option.textContent = specialty.name;
                                                    specialtiesSelect.appendChild(option);
                                                });
                                            }
                                        })
                                        .catch(error => console.error('Erro ao buscar especialidades:', error));
                                });

                                list.appendChild(item);
                            });
                        } else {
                            list.style.display = 'none'; // Oculta se não houver resultados
                        }
                    })
                    .catch(error => console.error('Erro ao buscar médicos:', error));
            } else {
                document.getElementById('doctor-list').style.display = 'none'; // Oculta se o texto for apagado
            }
        });
    }

    const patientSearch = document.getElementById('patient-search');
    if (patientSearch) {
        patientSearch.addEventListener('input', function () {
            const query = this.value;

            if (query.length > 0) {
                fetch(`/autocomplete/patient?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        const list = document.getElementById('patient-list');
                        list.innerHTML = ''; // Limpa a lista

                        if (data.length > 0) {
                            list.style.display = 'block';

                            data.forEach(patient => {
                                const item = document.createElement('li');
                                item.textContent = patient.name;
                                item.className = 'list-group-item';
                                item.style.cursor = 'pointer';

                                // Seleciona o paciente clicado
                                item.addEventListener('click', function () {
                                    patientSearch.value = patient.name;
                                    document.getElementById('patient_user_id').value = patient.id; // Atribui o ID ao campo oculto
                                    list.style.display = 'none'; // Oculta a lista
                                });

                                list.appendChild(item);
                            });
                        } else {
                            list.style.display = 'none'; // Oculta se não houver resultados
                        }
                    })
                    .catch(error => console.error('Erro ao buscar pacientes:', error));
            } else {
                document.getElementById('patient-list').style.display = 'none'; // Oculta se o texto for apagado
            }
        });
    }
});



