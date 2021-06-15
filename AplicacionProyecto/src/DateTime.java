import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;
import java.awt.SystemColor;
import java.awt.event.WindowEvent;
import java.awt.event.WindowFocusListener;
import java.beans.PropertyChangeEvent;
import java.beans.PropertyChangeListener;
import java.time.LocalDateTime;
import java.util.Calendar;
import java.util.Date;

import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JFormattedTextField;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JSlider;
import javax.swing.JTextField;
import javax.swing.border.EmptyBorder;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;
import javax.swing.text.NumberFormatter;

import com.toedter.calendar.JCalendar;

import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import javax.swing.SwingConstants;

public class DateTime extends JDialog {
	private static final long serialVersionUID = 1L;

	private final JPanel panel = new JPanel();
	private JPanel buttonPane;
	
	private JButton btnAceptar;
	private JButton btnLimpiar;
	private JButton btnCancelar;

	private JCalendar calendar;
	private LocalDateTime now;
	private JSlider slider;
	
	private JFormattedTextField txtHours;
	private JFormattedTextField txtMinutes;
	private JFormattedTextField txtSeconds;
	private JTextField txtf;

	private JLabel lblFecha;
	private JLabel lblHora;
	
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		try {
			DateTime dialog = new DateTime();
			dialog.setDefaultCloseOperation(JDialog.DISPOSE_ON_CLOSE);
			dialog.setVisible(true);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Create the dialog.
	 */
	public DateTime() {
		setResizable(false);
		setBounds(10, 10, 400, 340);
		getContentPane().setLayout(new BorderLayout());
		panel.setBorder(new EmptyBorder(5, 5, 5, 5));
		getContentPane().add(panel, BorderLayout.CENTER);
		panel.setLayout(null);
		setLocationRelativeTo(null);

		this.setTitle("DateTime");
		this.addWindowFocusListener(new WindowFocusListener() {
			public void windowGainedFocus(WindowEvent e) {
			}

			public void windowLostFocus(WindowEvent e) {	//Si clicas fuera desaparece
				DateTime.this.setVisible(false);
			}
		});
		now = LocalDateTime.now();
		JButton btnHoy = new JButton("Hoy");
		btnHoy.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				now = LocalDateTime.now();
				txtSeconds.setValue(now.getSecond());
				txtMinutes.setValue(now.getMinute());
				txtHours.setValue(now.getHour());
				calendar.setDate(new Date());
				actualizaFecha();	// Actualiza lblFecha
			}
		});
		btnHoy.setBounds(311, 231, 63, 23);
		btnHoy.setFocusable(false);
		panel.add(btnHoy);

		lblFecha = new JLabel("Fecha de hoy");
		lblFecha.setFont(new Font("Tahoma", Font.PLAIN, 15));
		lblFecha.setHorizontalAlignment(SwingConstants.CENTER);
		lblFecha.setBounds(9, 226, 75, 22);
		panel.add(lblFecha);

		lblHora = new JLabel("2021-4-3 15:56:31");
		lblHora.setFont(new Font("Tahoma", Font.PLAIN, 14));
		lblHora.setHorizontalAlignment(SwingConstants.CENTER);
		lblHora.setBounds(9, 247, 75, 22);
		panel.add(lblHora);

		startButtons();
		startCalendar();
		startTime();
		actualizaFecha();
	}

	public void startCalendar() {
		calendar = new JCalendar();
		calendar.setFont(new Font("Tahoma", Font.PLAIN, 16));
		calendar.getMonthChooser().getComboBox().setFont(new Font("Tahoma", Font.PLAIN, 12));
		calendar.setWeekOfYearVisible(false);
		calendar.setSundayForeground(Color.red);
		calendar.setBounds(0, 0, 385, 220);
		panel.add(calendar);
		calendar.addPropertyChangeListener(new PropertyChangeListener() {
			@Override
			public void propertyChange(PropertyChangeEvent e) {
				if ("calendar".equals(e.getPropertyName())) {	//Solo actualiza si ha cambiado la fecha
					actualizaFecha();
				}
			}
		});
	}

	public void startTime() {
		// Labels para poner :
		JLabel lblTime1 = new JLabel(":");
		lblTime1.setFont(new Font("Tahoma", Font.PLAIN, 16));
		lblTime1.setHorizontalAlignment(SwingConstants.CENTER);
		lblTime1.setBounds(162, 240, 10, 25);
		panel.add(lblTime1);

		JLabel lblTime2 = new JLabel(":");
		lblTime2.setFont(new Font("Tahoma", Font.PLAIN, 16));
		lblTime2.setHorizontalAlignment(SwingConstants.CENTER);
		lblTime2.setBounds(207, 240, 10, 25);
		panel.add(lblTime2);
		
		// Campos de texto formateados para guardar Horas, Minutos y Segundos
		NumberFormatter nf = new NumberFormatter();
		txtHours = new JFormattedTextField(nf);
		txtHours.setFont(new Font("Tahoma", Font.PLAIN, 16));
		txtHours.setHorizontalAlignment(SwingConstants.CENTER);
		txtHours.setBounds(125, 240, 40, 25);
		panel.add(txtHours);
		txtHours.setValue(now.getHour());
		txtHours.setColumns(10);
		txtHours.addPropertyChangeListener("value", new PropertyChangeListener() {
			@Override
			public void propertyChange(PropertyChangeEvent e) {
				double value = Double.parseDouble(txtHours.getText());
				if (value > 23) {
					txtHours.setValue(23);
				} else if (value < 0) {
					txtHours.setValue(0);
				}
				actualizaHora();
			}
		});

		txtMinutes = new JFormattedTextField(nf);
		txtMinutes.setColumns(10);
		txtMinutes.setFont(new Font("Tahoma", Font.PLAIN, 16));
		txtMinutes.setHorizontalAlignment(SwingConstants.CENTER);
		txtMinutes.setBounds(170, 240, 40, 25);
		panel.add(txtMinutes);
		txtMinutes.setValue(now.getMinute());
		txtMinutes.addPropertyChangeListener("value", new PropertyChangeListener() {
			@Override
			public void propertyChange(PropertyChangeEvent e) {
				double value = Double.parseDouble(txtMinutes.getText());
				if (value > 59) {
					txtMinutes.setValue(59);
				} else if (value < 0) {
					txtMinutes.setValue(0);
				}
				actualizaHora();
			}
		});

		txtSeconds = new JFormattedTextField(nf);
		txtSeconds.setColumns(10);
		txtSeconds.setFont(new Font("Tahoma", Font.PLAIN, 16));
		txtSeconds.setHorizontalAlignment(SwingConstants.CENTER);
		txtSeconds.setBounds(215, 240, 40, 25);
		panel.add(txtSeconds);
		txtSeconds.setValue(now.getSecond());
		txtSeconds.addPropertyChangeListener("value", new PropertyChangeListener() {
			@Override
			public void propertyChange(PropertyChangeEvent e) {
				double value = Double.parseDouble(txtSeconds.getText());
				if (value > 59) {
					txtSeconds.setValue(59);
				} else if (value < 0) {
					txtSeconds.setValue(0);
				}
				actualizaHora();
			}
		});

		slider = new JSlider();
		slider.setMinimum(0);
		slider.setMaximum(86399);
		slider.setBounds(90, 220, 200, 20);
		panel.add(slider);
		actualizaHora();
		slider.addChangeListener(new ChangeListener() {
			public void stateChanged(ChangeEvent e) {	//Setea las Labels al cambiar el Slider
				txtHours.setValue(slider.getValue() / 3600);
				txtMinutes.setValue((slider.getValue() % 3600) / 60);
				txtSeconds.setValue(slider.getValue() % 60);
			}
		});
	}

	public void actualizaHora() {
		int h = Integer.parseInt(txtHours.getText());
		int m = Integer.parseInt(txtMinutes.getText());
		int s = Integer.parseInt(txtSeconds.getText());
		String time = "";
		slider.setValue(h * 3600 + m * 60 + s);
		if (h < 10) {
			time += "0" + h;
		} else {
			time += h;
		}
		if (m < 10) {
			time += ":0" + m;
		} else {
			time += ":" + m;
		}
		if (s < 10) {
			time += ":0" + s;
		} else {
			time += ":" + s;
		}
		lblHora.setText(time);
	}

	public void actualizaFecha() {
		Calendar cal = calendar.getCalendar();
		lblFecha.setText(cal.get(Calendar.YEAR) + "-" + (cal.get(Calendar.MONTH)+1) + "-" + cal.get(Calendar.DAY_OF_MONTH));
	}

	public void startButtons() {
		buttonPane = new JPanel();
		buttonPane.setBackground(SystemColor.scrollbar);
		buttonPane.setBounds(0, 271, 384, 30);
		panel.add(buttonPane);
		buttonPane.setLayout(null);

		btnCancelar = new JButton("Cancelar");
		btnCancelar.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				dispose();
			}
		});
		btnCancelar.setBackground(SystemColor.menu);
		btnCancelar.setBounds(259, 5, 117, 20);
		btnCancelar.setFocusPainted(false);
		buttonPane.add(btnCancelar);

		btnAceptar = new JButton("Aceptar");
		btnAceptar.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				Calendar cal = calendar.getCalendar();
				int h = Integer.parseInt(txtHours.getText());
				int m = Integer.parseInt(txtMinutes.getText());
				int s = Integer.parseInt(txtSeconds.getText());
				txtf.setText(cal.get(Calendar.YEAR) + "-" + cal.get(Calendar.MONTH) + "-"
						+ cal.get(Calendar.DAY_OF_MONTH) + " " + h + ":" + m + ":" + s);
				dispose();
			}
		});
		btnAceptar.setBackground(SystemColor.menu);
		btnAceptar.setBounds(10, 5, 117, 20);
		btnAceptar.setFocusPainted(false);
		buttonPane.add(btnAceptar);
		
		btnLimpiar = new JButton("Limpiar");
		btnLimpiar.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				txtf.setText(null);
				dispose();
			}
		});
		btnLimpiar.setBackground(SystemColor.menu);
		btnLimpiar.setBounds(135, 5, 117, 20);
		btnLimpiar.setFocusPainted(false);
		buttonPane.add(btnLimpiar);
	}

	public void setJTextField(JTextField txt) {
		txtf = txt;
	}
}
