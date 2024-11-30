import ContactForm from "../../Component/ContactComponent/ContactForm"
import Maps from "../../Component/ContactComponent/Maps"

const Contact:React.FC = () => {
    return (
        <>
        <main className="main">
            <Maps />
            <ContactForm />
        </main>
        </>
    )
}

export default Contact