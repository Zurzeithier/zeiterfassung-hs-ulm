using System;
using System.Collections.Generic;
using System.Windows.Forms;

namespace Zeiterfassung.NET
{

    /*
     * Author: Ralph Greschner
     * Date: 04/05/2008
     * 
     * Controller class for the application (MVC),
     * should land in separate file (
     */

    class MainController
    {
        private LoginForm loginForm;
        private MainForm mainForm;
        private Backend backend;

        public MainController()
        {
            // Create business model
            backend = new Backend();

            // Create views
            loginForm = new LoginForm();
            mainForm = new MainForm();

            // Supply business models for Forms (dirty hack)
            loginForm.backend = this.backend;
            mainForm.backend = this.backend;
            SubscribeAll();
        }
        private void SetViewSubscriptionsOnModel()
        {
            backend.delLoginStatusChanged += new Action<StatusCode>(loginForm.LoginStatusChanged);
            backend.delLoginStatusChanged += new Action<StatusCode>(this.OnLoginStatusChanged);
            backend.delLoginStatusChanged += new Action<StatusCode>(mainForm.OnLoginStatusChanged);
        }
        private void SetModelSubscriptionsOnView()
        {
            loginForm.delExitApp += new Action<int>(this.OnExitApp);
            mainForm.delExitApp += new Action<int>(this.OnExitApp);
        }
        public void SubscribeAll()
        {
            SetViewSubscriptionsOnModel();
            SetModelSubscriptionsOnView();

        }
        public void OnExitApp(int n)
        {
            System.Windows.Forms.MessageBox.Show("blubb");
            Application.Exit();
        }
        public void OnLoginStatusChanged(StatusCode status)
        {

        }

        // Start of application flow
        public void Run()
        {
            loginForm.Show();
        }

    }
    
    static class Program
    {
        
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            MainController c = new MainController();
            c.Run();

            Application.Run();
        }
    }
}