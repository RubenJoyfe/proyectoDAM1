import java.awt.EventQueue;
import java.sql.*;

import javax.swing.JFrame;
import javax.swing.JPanel;
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

	private JMenuItem mntmTabla[];

	
	private Connection conexion;

	private Statement comando;

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
		setBounds(100, 100, 1024, 720);
		
		contentPane = new JPanel();
		contentPane.setBorder(new EmptyBorder(5, 5, 5, 5));
		setContentPane(contentPane);
		contentPane.setLayout(null);
		
		conexionMysql("localhost", "h15af00", "root", "");
		startMenu();
		
		
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
				mntmTabla[rows] = new JMenuItem(rs.getString("nombre"));
				mnMostrar.add(mntmTabla[rows]);
				rows++;
			}
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
			
	
	}

	public void conexionMysql(String ip, String bbdd, String usuario, String pass) {
		try {
			conexion = DriverManager.getConnection(
			        "jdbc:mysql://" + ip + "/" + bbdd, usuario, pass);//jdbc:mysql://<ip>:<puerto>/<nombreDB>,"user","pass"
		} catch (SQLException e) {
			System.out.println("No se ha podido conectar a la base de datos. Error: " + e.getMessage());
			e.printStackTrace();
		}
	}

}
