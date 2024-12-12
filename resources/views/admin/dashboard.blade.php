@extends('layouts.admin')
@section('content')
<style>
    .stat-card {
        border-radius: 10px;
        color: white;
        padding: 20px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
    }
    .stat-card:hover {
        transform: scale(1.05);
    }
    .stat-card .icon {
        font-size: 40px;
        margin-bottom: 10px;
    }
    .stat-card .number {
        font-size: 32px;
        font-weight: bold;
    }
    .stat-card .description {
        font-size: 16px;
    }
    .stat-card .more-info {
        margin-top: 10px;
        display: block;
        color: white;
        text-decoration: none;
    }
    .stat-card .more-info:hover {
        text-decoration: underline;
    }
    .chart-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table thead {
        background: #f8f9fa;
    }
</style>

<div class="container my-5">
    <h1 class="text-center mb-4">Dashboard - Clínica</h1>
    
    <!-- KPIs -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="icon">👨‍⚕️</div>
                <div class="number">{{ $totalMedicos }}</div>
                <div class="description">Médicos</div>
                <a href="#" class="more-info">Mais informações →</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="icon">🩺</div>
                <div class="number">{{ $totalPacientes }}</div>
                <div class="description">Pacientes Atendidos</div>
                <a href="#" class="more-info">Mais informações →</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-warning">
                <div class="icon">📅</div>
                <div class="number">{{ $totalConsultasRealizadas }}</div>
                <div class="description">Consultas Realizadas</div>
                <a href="#" class="more-info">Mais informações →</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-danger">
                <div class="icon">💉</div>
                <div class="number">{{ $totalEspecialidades }}</div>
                <div class="description">Especialidades</div>
                <a href="#" class="more-info">Mais informações →</a>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="row my-5">
        <div class="col-12 chart-container">
            <h4 class="text-center mb-4">Tendência de Consultas</h4>
            <canvas id="chart-consultas"></canvas>
        </div>
    </div>

    <!-- Tabela -->
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">Consultas Recentes</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>João Silva</td>
                        <td>Dra. Ana</td>
                        <td>30/11/2024</td>
                        <td>Concluída</td>
                    </tr>
                    <tr>
                        <td>Maria Oliveira</td>
                        <td>Dr. Carlos</td>
                        <td>29/11/2024</td>
                        <td>Pendente</td>
                    </tr>
                    <tr>
                        <td>Pedro Santos</td>
                        <td>Dra. Clara</td>
                        <td>28/11/2024</td>
                        <td>Cancelada</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chart-consultas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Consultas',
                data: [50, 70, 80, 120, 150, 200],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endsection
