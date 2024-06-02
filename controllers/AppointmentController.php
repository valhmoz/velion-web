<?php

require_once '../models/AppointmentModel.php';
require_once '../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta para tu configuración

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class AppointmentController
{
    private $appointmentModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
    }

    public function obtenerCitasPaginadas($iniciar, $articulos_x_pagina)
    {
        return $this->appointmentModel->obtenerCitasPaginadas($iniciar, $articulos_x_pagina);
    }

    public function obtenerCitas()
    {
        return $this->appointmentModel->read('citas');
    }

    public function obtenerCitasUsuarioPaginadas($DNI, $iniciar, $articulos_x_pagina)
    {
        return $this->appointmentModel->obtenerCitasUsuarioPaginadas($DNI, $iniciar, $articulos_x_pagina);
    }

    public function obtenerCitasUsuario($DNI, $isApiRequest = false)
    {
        $citas =  $this->appointmentModel->obtenerCitasUsuario($DNI);

        if ($isApiRequest) {
            return $citas;
        }
    
        if (!$citas) {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha encontrado ninguna cita médica para el usuario seleccionado.');
            header("Location: ../pages/appointments-patients.php");
            exit();
        }
        return $citas;
    }

    public function obtenerListaPacientes()
    {
        return $this->appointmentModel->read('usuarios', 'rol = \'Paciente\'');
    }

    public function obtenerListaFisioterapeutas()
    {
        return $this->appointmentModel->read('usuarios', 'rol = \'Fisioterapeuta\'');
    }

    public function obtenerEspecialidades()
    {
        return $this->appointmentModel->read('especialidades');
    }

    public function asignarCita($tabla, $datos)
    {
        if ($this->appointmentModel->insert($tabla, $datos)) {
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Cita añadida correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha podido añadir la cita correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        }
    }

    public function asignarCitaPatients($tabla, $datos)
    {
        if ($this->appointmentModel->insert($tabla, $datos)) {
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Cita añadida correctamente.');
            header('Location: ../pages/appointments-patients.php');
            exit();
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha podido añadir la cita correctamente.');
            header('Location: ../pages/appointments-patients.php');
            exit();
        }
    }

    public function editarCita($tabla, $datos, $condicion)
    {
        if ($this->appointmentModel->update($tabla, $datos, $condicion)) {
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Cita actualizada correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha podido actualizar la cita correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        }
    }

    public function eliminarCita($tabla, $condicion)
    {
        if ($this->appointmentModel->delete($tabla, $condicion)) {
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Cita eliminada correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha podido eliminar la cita correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        }
    }

    public function eliminarCitaPatient($tabla, $condicion)
    {
        if ($this->appointmentModel->delete($tabla, $condicion)) {
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Cita eliminada correctamente.');
            header('Location: ../pages/appointments-patients.php');
            exit();
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha podido eliminar la cita correctamente.');
            header('Location: ../pages/appointments-patients.php');
            exit();
        }
    }

    public function confirmarCita($tabla, $datos, $condicion)
    {
        if ($this->appointmentModel->update($tabla, $datos, $condicion)) {
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Cita eliminada correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se ha podido eliminar la cita correctamente.');
            header('Location: ../pages/appointments.php');
            exit();
        }
    }

    public function buscarCitas($filtro_usuario_id, $filtro_fecha_hora, $filtro_estado, $filtro_especialidad)
    {

        $citasFiltradas = $this->appointmentModel->buscarCitas($filtro_usuario_id, $filtro_fecha_hora, $filtro_estado, $filtro_especialidad);
        if (!empty($citasFiltradas)) {
            return $citasFiltradas;
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se han encontrado citas con los criterios especificados.');
            header('Location: ../pages/appointments.php');
            exit();
        }
    }

    public function buscarCitasPatients($filtro_usuario_id, $filtro_fecha_hora, $filtro_estado, $filtro_especialidad)
    {
        $citasFiltradas = $this->appointmentModel->buscarCitas($filtro_usuario_id, $filtro_fecha_hora, $filtro_estado, $filtro_especialidad);
        if (!empty($citasFiltradas)) {
            return $citasFiltradas;
        } else {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'No se han encontrado la citas con los criterios especificados.');
            header('Location: ../pages/appointments-patients.php');
            exit();
        }
    }

}
