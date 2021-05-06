import java.awt.BorderLayout;
import java.awt.EventQueue;
import java.awt.GridLayout;
import java.sql.*;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JPasswordField;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.border.EmptyBorder;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.JMenuBar;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.awt.Font;
import javax.swing.border.LineBorder;
import java.awt.Color;
import javax.swing.JButton;

public class Main extends JFrame {
	private static final long serialVersionUID = 4219163702005532108L;
	
	private JPanel contentPane;
	private JPanel panel_opciones;
	private JPanel panel_tabla;
	private JButton btnInsertar;
	private JButton btnModificar;
	private JButton btnEliminar;
	private JLabel lblNombreTabla;
	private JMenu mnMostrar;
	private JMenuBar menuBar;
	private JMenuItem mntmTabla[];
	private JTable tabla;
	private JScrollPane scroll = new JScrollPane();
	private String titulos [];
	private String tipos[];
	private String[] txtEditables;
	private String nombre  = "",  pass = "";
	
	private Connection conexion;
	private Statement comando;
	private Inserts insertacion;
	private Deletes eliminacion;
	private Updates modificacion;
	private Object celdas [][];
	private Editables[] editaciones = new Editables[0];
	
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					Main frame = new Main();
					frame.setVisible(true);
					frame.setTitle("Base de Datos h15af00");
					frame.setResizable(false);
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
		panel_tabla.setBounds(10, 11, 619, 433);
		contentPane.add(panel_tabla);
		
		panel_opciones = new JPanel();
		panel_opciones.setBorder(new LineBorder(Color.GRAY));
		panel_opciones.setBounds(638, 11, 184, 502);
		contentPane.add(panel_opciones);
		panel_opciones.setLayout(null);
		
		lblNombreTabla = new JLabel("TABLA");
		lblNombreTabla.setFont(new Font("Tahoma", Font.PLAIN, 20));
		lblNombreTabla.setHorizontalAlignment(SwingConstants.CENTER);
		lblNombreTabla.setBounds(10, 11, 164, 51);
		panel_opciones.add(lblNombreTabla);
		
		btnInsertar = new JButton("Insertar");
		btnInsertar.addActionListener(new ActionListener() { // -------------------------> Listener Inserts
			public void actionPerformed(ActionEvent e) {
				getEditableString();
				inserts(); 
			}
		});
		btnInsertar.setBounds(10, 434, 164, 57);
		panel_opciones.add(btnInsertar);
		
		btnModificar = new JButton("Modificar");
		btnModificar.addActionListener(new ActionListener() { // ----------------------> Listener Modificar
			public void actionPerformed(ActionEvent e) {
				String msg = "¿Está seguro que quiere modificar este"
						+ " registro de la tabla " + lblNombreTabla.getText() + "?";
				int op = JOptionPane.showConfirmDialog(null, msg,
			            "Confirmacion", JOptionPane.YES_NO_OPTION, JOptionPane.WARNING_MESSAGE);
				if (op==0) {
					getEditableString();
					modificar();
				}
				
			}
		});
		btnModificar.setBounds(10, 366, 164, 57);
		panel_opciones.add(btnModificar);
		
		btnEliminar = new JButton("Eliminar");
		btnEliminar.addActionListener(new ActionListener() { // ----------------------> Listener Eliminar
			public void actionPerformed(ActionEvent e) {
				String msg = "¿Está seguro que quiere eliminar este"
						+ " registro de la tabla " + lblNombreTabla.getText() + "?";
				int op = JOptionPane.showConfirmDialog(null, msg,
			            "Confirmacion", JOptionPane.YES_NO_OPTION, JOptionPane.WARNING_MESSAGE);
				if (op==0) {
					getEditableString();
					eliminar();
				}
				
			}
		});
		btnEliminar.setBounds(10, 298, 164, 57);
		panel_opciones.add(btnEliminar);
		
		do {
//			login();
			nombre = "root";
			pass = "";
		}while (!conexionMysql("localhost", "h15af00", nombre, pass));
		startMenu();
		crearTabla("vacía");
		insertacion = new Inserts(conexion);
		eliminacion = new Deletes(conexion);
		modificacion = new Updates(conexion);
	}
	
	//********************************* Operaciones MYSQL *****************************************
	public void inserts() {
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = insertacion.insertarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = insertacion.insertarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = insertacion.insertarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = insertacion.insertarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = insertacion.insertarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = insertacion.insertarJuego(txtEditables);		
			break;
			
		case "ajustes":
			resultado = insertacion.insertarAjustes(txtEditables);	
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		if (resultado==0) {
			crearTabla(acTab);
			JOptionPane.showMessageDialog(null, "Todo correcto", "Success", JOptionPane.INFORMATION_MESSAGE, null);
		} else {
			JOptionPane.showMessageDialog(null, "Error "+resultado, "Error", JOptionPane.ERROR_MESSAGE, null);
		}
	}
	
	public void eliminar(){
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = eliminacion.eliminarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = eliminacion.eliminarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = eliminacion.eliminarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = eliminacion.eliminarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = eliminacion.eliminarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = eliminacion.eliminarJuego(txtEditables);
			break;
			
		case "ajustes":
			resultado = eliminacion.eliminarAjustes(txtEditables);
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		if (resultado==0) {
			crearTabla(acTab);
			JOptionPane.showMessageDialog(null, "Todo correcto", "Success", JOptionPane.INFORMATION_MESSAGE, null);
		} else {
			JOptionPane.showMessageDialog(null, "Error "+resultado, "Error", JOptionPane.ERROR_MESSAGE, null);
		}
	}
	
	public void modificar(){
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = modificacion.ModificarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
//			resultado = ;
			break;
			
		case "usuario_desbloqueo":
//			resultado = ;
			break;
			
		case "usuario_juego":
//			resultado = ;
			break;
			
		case "puntuacion":
//			resultado = ;
			break;
			
		case "juego":
//			resultado = ;
			break;
			
		case "ajustes":
//			resultado = ;
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		if (resultado==0) {
			crearTabla(acTab);
			JOptionPane.showMessageDialog(null, "Todo correcto", "Success", JOptionPane.INFORMATION_MESSAGE, null);
		} else {
			JOptionPane.showMessageDialog(null, "Error "+resultado, "Error", JOptionPane.ERROR_MESSAGE, null);
		}
	}
	
	
	//********************************* Fin MYSQL *************************************************
	
	public void startMenu() {
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
				String nombreOp = rs.getString("nombre");
				String nombreMay = rs.getString("nombre").substring(0, 1).toUpperCase()+ rs.getString("nombre").substring(1);
				mntmTabla[rows] = new JMenuItem(nombreMay);
				mnMostrar.add(mntmTabla[rows]);
				mntmTabla[rows].addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent e) {
						crearTabla(nombreOp);
						lblNombreTabla.setText(nombreMay);
					}
				});
				rows++;
			}
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
			
	
	}

	public void startEditables(String columnas []) {
		deleteEditables();
		editaciones = new Editables[columnas.length];
		for (int i = 0; i < columnas.length; i++) {
			editaciones[i] = new Editables(columnas[i], tipos[i], i,  contentPane);
		}
	}
	
	public void deleteEditables() {
		for (int i = 0; i < editaciones.length; i++) {
			editaciones[i].remove();
		}
		contentPane.repaint();
	}
	
	public void getEditableString() {
		txtEditables = new String[editaciones.length];
		for (int i = 0; i < editaciones.length; i++) {
			txtEditables[i] = editaciones[i].getText();
		}
	}
	
	public boolean conexionMysql(String ip, String bbdd, String usuario, String pass) {
		try {
			conexion = DriverManager.getConnection(
			        "jdbc:mysql://" + ip + "/" + bbdd + "?serverTimezone=UTC", usuario, pass);//jdbc:mysql://<ip>:<puerto>/<nombreDB>,"user","pass"
			return true;
		} catch (SQLException e) {
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
		if (tb.equals("vacía")) {
			tabla = new JTable();
			panel_tabla.setLayout(null);
			scroll.setViewportView(tabla);
			scroll.setBounds(0, 0, 618, 502);
		    panel_tabla.add(scroll);
		} else {
			int nCol=0, nRow=0;
			ResultSet rs;
			String sql = "SELECT * FROM " + tb +";";
			String sql2= "SELECT COLUMN_NAME as nombreColumna FROM information_schema.COLUMNS WHERE "
					+ "TABLE_SCHEMA  LIKE 'h15af00' AND TABLE_NAME = '"+tb+"'";
			String sql3 = "SELECT COLUMN_TYPE as tipos FROM information_schema.COLUMNS "
					+ "WHERE TABLE_NAME = '"+tb+"'";
			
			try {
				int cont=0;
				
				rs = comando.executeQuery(sql2);
				while (rs.next()) {nCol++;} //cuenta el numero de columnas
				titulos = new String [nCol]; //nCol asigna su tamano
				tipos = new String[nCol];
				rs = comando.executeQuery(sql2);
				while (rs.next()) { 
					titulos[cont]=rs.getString("nombreColumna");
					cont++;
				}
				cont = 0;
				rs = comando.executeQuery(sql3);
				while (rs.next()) { 
					tipos[cont]=rs.getString("tipos");
					cont++;
				}
				startEditables(titulos);
				ResultSet rs2 = comando.executeQuery(sql);
				while (rs2.next()) {nRow++;}
				celdas=new Object[nRow][nCol];
				cont=0;
				int cont2=0;
				rs2 = comando.executeQuery(sql);
				while (rs2.next()) {
					while (cont2<nCol) {
						celdas[cont][cont2]=rs2.getString(titulos[cont2]);
						cont2++;
					}
					cont++;
					cont2=0;
				}
				tabla=new JTable(celdas, titulos);
			} 
			catch (SQLException e) {
				e.printStackTrace();
			}
		}
		tabla.setName(tb);
		panel_tabla.setLayout(null);
		scroll.setViewportView(tabla);
		scroll.setBounds(0, 0, 619, 433);
	    panel_tabla.add(scroll);
	    tabla.setCellSelectionEnabled(true);
	    tabla.setAutoCreateRowSorter(true);
		tabla.getSelectionModel().addListSelectionListener(new ListSelectionListener(){
	        public void valueChanged(ListSelectionEvent event) {
	        	if(event.getValueIsAdjusting()) {
	        		int n = tabla.getSelectedRow();
//	        		System.out.println(tabla.convertRowIndexToModel(fila));
	        		if (n>=0) {
	        			int fila = tabla.convertRowIndexToModel(n);
						for (int i = 0; i < editaciones.length; i++) {
							editaciones[i].setText(""+tabla.getModel().getValueAt(fila, i));
						}
					}
	        	}
	        }
	    });
	}
}
