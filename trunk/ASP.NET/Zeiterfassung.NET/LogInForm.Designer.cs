namespace Zeiterfassung.NET
{
    partial class LogIn
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.lbl_server = new System.Windows.Forms.Label();
            this.lbl_user = new System.Windows.Forms.Label();
            this.lbl_pass = new System.Windows.Forms.Label();
            this.lbl_database = new System.Windows.Forms.Label();
            this.tb_server = new System.Windows.Forms.TextBox();
            this.tb_user = new System.Windows.Forms.TextBox();
            this.tb_pass = new System.Windows.Forms.TextBox();
            this.tb_database = new System.Windows.Forms.TextBox();
            this.btn_login = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // lbl_server
            // 
            this.lbl_server.AutoSize = true;
            this.lbl_server.Location = new System.Drawing.Point(40, 39);
            this.lbl_server.Name = "lbl_server";
            this.lbl_server.Size = new System.Drawing.Size(41, 13);
            this.lbl_server.TabIndex = 0;
            this.lbl_server.Text = "Server:";
            // 
            // lbl_user
            // 
            this.lbl_user.AutoSize = true;
            this.lbl_user.Location = new System.Drawing.Point(40, 84);
            this.lbl_user.Name = "lbl_user";
            this.lbl_user.Size = new System.Drawing.Size(32, 13);
            this.lbl_user.TabIndex = 1;
            this.lbl_user.Text = "User:";
            // 
            // lbl_pass
            // 
            this.lbl_pass.AutoSize = true;
            this.lbl_pass.Location = new System.Drawing.Point(40, 123);
            this.lbl_pass.Name = "lbl_pass";
            this.lbl_pass.Size = new System.Drawing.Size(56, 13);
            this.lbl_pass.TabIndex = 2;
            this.lbl_pass.Text = "Password:";
            // 
            // lbl_database
            // 
            this.lbl_database.AutoSize = true;
            this.lbl_database.Location = new System.Drawing.Point(40, 168);
            this.lbl_database.Name = "lbl_database";
            this.lbl_database.Size = new System.Drawing.Size(56, 13);
            this.lbl_database.TabIndex = 3;
            this.lbl_database.Text = "Database:";
            // 
            // tb_server
            // 
            this.tb_server.Location = new System.Drawing.Point(114, 39);
            this.tb_server.Name = "tb_server";
            this.tb_server.Size = new System.Drawing.Size(148, 20);
            this.tb_server.TabIndex = 4;
            // 
            // tb_user
            // 
            this.tb_user.Location = new System.Drawing.Point(114, 84);
            this.tb_user.Name = "tb_user";
            this.tb_user.Size = new System.Drawing.Size(148, 20);
            this.tb_user.TabIndex = 5;
            // 
            // tb_pass
            // 
            this.tb_pass.Location = new System.Drawing.Point(114, 123);
            this.tb_pass.Name = "tb_pass";
            this.tb_pass.PasswordChar = '*';
            this.tb_pass.Size = new System.Drawing.Size(148, 20);
            this.tb_pass.TabIndex = 6;
            // 
            // tb_database
            // 
            this.tb_database.Location = new System.Drawing.Point(114, 168);
            this.tb_database.Name = "tb_database";
            this.tb_database.Size = new System.Drawing.Size(148, 20);
            this.tb_database.TabIndex = 7;
            // 
            // btn_login
            // 
            this.btn_login.Location = new System.Drawing.Point(97, 211);
            this.btn_login.Name = "btn_login";
            this.btn_login.Size = new System.Drawing.Size(81, 23);
            this.btn_login.TabIndex = 8;
            this.btn_login.Text = "LogIn";
            this.btn_login.UseVisualStyleBackColor = true;
            this.btn_login.Click += new System.EventHandler(this.btn_login_Click);
            // 
            // LogIn
            // 
            this.AcceptButton = this.btn_login;
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(294, 256);
            this.Controls.Add(this.btn_login);
            this.Controls.Add(this.tb_database);
            this.Controls.Add(this.tb_pass);
            this.Controls.Add(this.tb_user);
            this.Controls.Add(this.tb_server);
            this.Controls.Add(this.lbl_database);
            this.Controls.Add(this.lbl_pass);
            this.Controls.Add(this.lbl_user);
            this.Controls.Add(this.lbl_server);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Name = "LogIn";
            this.Text = "LogIn";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label lbl_server;
        private System.Windows.Forms.Label lbl_user;
        private System.Windows.Forms.Label lbl_pass;
        private System.Windows.Forms.Label lbl_database;
        private System.Windows.Forms.TextBox tb_server;
        private System.Windows.Forms.TextBox tb_user;
        private System.Windows.Forms.TextBox tb_pass;
        private System.Windows.Forms.TextBox tb_database;
        private System.Windows.Forms.Button btn_login;
    }
}

