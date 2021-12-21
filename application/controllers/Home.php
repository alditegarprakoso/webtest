<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HomeModel', 'home');
    }

    public function index()
    {
        $this->load->view('index.php');
    }

    public function login()
    {

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

        if ($this->form_validation->run() == false) {
            $this->load->view('login');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->db->get_where('user', ['email' => $email])->row_array();

            if ($user) {
                if ($user['aktif'] == 1) {
                    if (password_verify($password, $user['password'])) {
                        $data = [
                            'email' => $user['email'],
                        ];
                        $this->session->set_userdata($data);
                        redirect('home/dashboard');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-warning">Wrong password !</div>');
                        redirect('home');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning">Email has not been activated !</div>');
                    redirect('home');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Email is not registered</div>');
                redirect('home');
            }
        }
    }

    public function dashboard()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('dashboard', $data);
    }

    public function daftar()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|alpha_numeric_spaces|min_length[3]|regex_match[/^([a-zA-Z]+\s)*[a-zA-Z]+$/]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[password_2]');
        $this->form_validation->set_rules('password_2', 'Ulangi Password', 'trim|required|min_length[6]|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('daftar');
        } else {
            $data['name'] = $this->input->post('nama');
            $data['email'] = $this->input->post('email');
            $data['aktif'] = '0';
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

            $config['allowed_types'] = 'jpeg|jpg|png|svg';
            $config['max_size'] = '1024';
            $config['upload_path'] = './assets/images/foto/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $data['foto'] = $this->upload->data()['file_name'];
            } else {
                $this->session->set_flashdata('nama', $this->input->post('nama'));
                $this->session->set_flashdata('email', $this->input->post('email'));
                $this->session->set_flashdata('error_image', '<small class="text-danger">' . $this->upload->display_errors() . '</small>');
                redirect('home/daftar');
            }

            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $this->input->post('email'),
                'token' => $token,
            ];

            $this->home->insert($data);
            $this->db->insert('user_token', $user_token);
            $this->sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success">Congratulations! You have successfully registered, please verify your email now</div>');
            redirect('home');
        }
    }

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('forgot_password');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'aktif' => 1])->row_array();

            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $this->input->post('email'),
                    'token' => $token,
                ];

                $this->db->insert('user_token', $user_token);
                $this->sendEmail($token, 'forgot');
                $this->session->set_flashdata('message', '<div class="alert alert-success">Please check your email to reset your password!</div>');
                redirect('home');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Email is not registered or activated!</div>');
                redirect('home');
            }
        }
    }

    private function sendEmail($token, $type)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'hikarushiro27@gmail.com',
            'smtp_pass' => 'abc12341234~',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];
        // $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('hikarushiro27@gmail.com', 'Webtest Coralis');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {
            $this->email->subject('Verification Account');
            $this->email->message('Please click link to Verification your Account : <a href="' . base_url() . 'home/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Active</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Please click link to Reset your Password : <a href="' . base_url() . 'home/resetPassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                $this->db->set('aktif', 1);
                $this->db->where('email', $email);
                $this->db->update('user');

                $this->db->delete('user_token', ['email' => $email]);
                $this->session->set_flashdata('message', '<div class="alert alert-success">Congratulation! Your Account has been created. Please Login</div>');
                redirect('home');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Activation Account Fail! Wrong Token</div>');
                redirect('home');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Activation Account Fail! Wrong Email</div>');
            redirect('home');
        }
    }

    public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Reset Password Fail! Wrong Token</div>');
                redirect('home');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Reset Password Fail! Wrong Email</div>');
            redirect('home');
        }
    }

    public function changePassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('home');
        }

        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[6]|matches[password1]');

        if ($this->form_validation->run() == FALSE) {
            $data['email'] = $this->session->userdata('reset_email');
            $this->load->view('change_password', $data);
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');
            $this->session->set_flashdata('message', '<div class="alert alert-success">Password has been change, please login</div>');
            redirect('home');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->set_flashdata('message', '<div class="alert alert-success">You have been logout</div>');
        redirect('home');
    }
}
