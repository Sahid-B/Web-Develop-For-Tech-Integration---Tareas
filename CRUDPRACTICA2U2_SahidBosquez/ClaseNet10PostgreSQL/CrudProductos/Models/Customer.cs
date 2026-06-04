using System.ComponentModel.DataAnnotations;

namespace CrudProductos.Models
{
    public class Customer
    {
        public int Id { get; set; }

        [Required(ErrorMessage = "El nombre es obligatorio")]
        [StringLength(100)]
        public string Nombre { get; set; } = string.Empty;

        [Required(ErrorMessage = "El correo es obligatorio")]
        [StringLength(150)]
        [EmailAddress(ErrorMessage = "El correo no es válido")]
        public string Email { get; set; } = string.Empty;

        [Required(ErrorMessage = "El teléfono es obligatorio")]
        [StringLength(20)]
        public string Telefono { get; set; } = string.Empty;

        [Range(0, 99999.99, ErrorMessage = "El saldo no puede ser negativo")]
        public decimal Saldo { get; set; }

        public bool Activo { get; set; } = true;

        public DateTime FechaRegistro { get; set; } = DateTime.UtcNow;
    }
}

