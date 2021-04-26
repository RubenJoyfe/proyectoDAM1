import java.awt.BorderLayout;
import java.awt.EventQueue;
import java.awt.GridLayout;
import java.sql.*;
import java.util.Hashtable;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JPasswordField;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.border.EmptyBorder;
import javax.swing.JMenuBar;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;

public class Main extends JFrame {
	private static final long serialVersionUID = 4219163702005532108L;
	
	private JPanel contentPane;
	private JMenuBar menuBar;
	private JMenu mnMostrar;
	private String nombre,  pass;
	private JMenuItem mntmTabla[];
	private Connection conexion;
	private Statement comando;
	private JPanel panel_tabla;
	
	
	
	private String titulos []={"Encabezado 1", "Encabezado 2"};
	private Object celdas [][]=new Object[99][2];
	private JScrollPane scroll = new JScrollPane();
	private JTable tabla=new JTable(celdas, titulos);

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					Main frame = new Main();
					frame.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the frame.
	 */
	public Main() {
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setBounds(100, 100, 848, 585);
		
		contentPane = new JPanel();
		contentPane.setBorder(new EmptyBorder(5, 5, 5, 5));
		setContentPane(contentPane);
		contentPane.setLayout(null);
		
		panel_tabla = new JPanel();
		panel_tabla.setBounds(10, 11, 618, 502);
		contentPane.add(panel_tabla);
		do {
//			login();
			nombre = "Ruben";
			pass = "1234";
		}while (!conexionMysql("localhost", "h15af00", nombre, pass));
		
		startMenu();
		scroll.setViewportView(tabla);
		scroll.setBounds(20, 20, 200, 100);
	    panel_tabla.add(scroll);
		
	}
	
	void startMenu() {
		menuBar = new JMenuBar();
		setJMenuBar(menuBar);
		mnMostrar = new JMenu("Tablas");

		menuBar.add(mnMostrar);
		
		String sql = "SELECT table_name AS nombre FROM information_schema.tables"
				+ " WHERE table_schema = 'h15af00' ORDER BY nombre ASC";
		try {
			int rows=0;
			comando = conexion.createStatement();
			ResultSet rs = comando.executeQuery(sql);
			
			while (rs.next()) {rows++;} //contar el numero de filas que devuelve
			mntmTabla = new JMenuItem [rows];
			rs = comando.executeQuery(sql);
			
			rows=0;
			while (rs.next()) {
				mntmTabla[rows] = new JMenuItem(rs.getString("nombre").substring(0, 1).toUpperCase()
						+ rs.getString("nombre").substring(1));
				mnMostrar.add(mntmTabla[rows]);
				rows++;
			}
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
			
	
	}

	public boolean conexionMysql(String ip, String bbdd, String usuario, String pass) {
		try {
			conexion = DriverManager.getConnection(
			        "jdbc:mysql://" + ip + "/" + bbdd, usuario, pass);//jdbc:mysql://<ip>:<puerto>/<nombreDB>,"user","pass"
			return true;
		} catch (SQLException e) {
//			System.out.println("No se ha podido conectar a la base de datos. Error: " + e.getMessage());
			e.printStackTrace();
		}
		return false;
	}
	
	public void login() {
		JPanel panel = new JPanel(new BorderLayout(5, 5));
		

			JPanel label = new JPanel(new GridLayout(0, 1, 2, 2));
		    label.add(new JLabel("Usuario", SwingConstants.RIGHT));
		    label.add(new JLabel("Contraseña", SwingConstants.RIGHT));
		    panel.add(label, BorderLayout.WEST);
		    
		    JPanel controls = new JPanel(new GridLayout(0, 1, 2, 2));
		    JTextField username = new JTextField();
		    controls.add(username);
		    JPasswordField password = new JPasswordField();
		    controls.add(password);
		    panel.add(controls, BorderLayout.CENTER);
		    
		    int op = JOptionPane.showConfirmDialog(this, panel, "login", JOptionPane.OK_CANCEL_OPTION);
		    if (op==2||op==-1) {
		    	System.exit(0);
			}
		    
		    nombre = username.getText();
		    pass = new String(password.getPassword());		   
	}
	
	public void crearTabla(String tb) {
		String sql = "SELECT * FROM "+tb+";";
		try { ResultSet rs = comando.executeQuery(sql);}
		catch (SQLException e) {e.printStackTrace();}
		
		titulos = new String [1];
		celdas  = new Object [1][1];
		JScrollPane scroll = new JScrollPane();
		tabla=new JTable(celdas, titulos);
	
	}
}
